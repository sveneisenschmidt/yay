<?php

namespace Component\HttpFoundation\Tests\Request\ParamConverter;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;
use Component\HttpFoundation\Request\ParamConverter\DeserializeFieldConverter;

class DeserializeFieldConverterFixture
{
    /**
     * @JMS\Serializer\Annotation\Type("string")
     * @JMS\Serializer\Annotation\Groups({"test1"})
     */
    public $bar1 = '';

    /**
     * @JMS\Serializer\Annotation\Type("string")
     * @JMS\Serializer\Annotation\Groups({"test2"})
     */
    public $bar2 = '';
}

class DeserializeFieldConverterTest extends TestCase
{
    public function createConfiguration(
        string $converterName = 'DeserializeField',
        string $parameterName = 'foo',
        array $options = []
    ): ParamConverter {
        $configuration = $this->getMockBuilder(ParamConverter::class)
                              ->disableOriginalConstructor()
                              ->setMethods(['getConverter', 'getName', 'getOptions'])
                              ->getMock();

        $configuration->method('getConverter')
                      ->willReturn($converterName);

        $configuration->method('getName')
                      ->willReturn($parameterName);

        $configuration->method('getOptions')
                    ->willReturn($options);

        return $configuration;
    }


    public function createSerializer(): SerializerInterface
    {
        return SerializerBuilder::create()->build();
    }

    public function test_param_converter_is_supported(): void
    {
        $configuration = $this->createConfiguration();
        $serializer = $this->createSerializer();

        $this->assertTrue((new DeserializeFieldConverter($serializer))->supports($configuration));
    }

    public function test_param_converter_is_not_supported(): void
    {
        $configuration = $this->createConfiguration('DeserializeField2');
        $serializer = $this->createSerializer();

        $this->assertFalse((new DeserializeFieldConverter($serializer))->supports($configuration));
    }

    public function test_object_is_deserialized_and_applied_as_attribute(): void
    {
        $options = ['group' => 'test1', 'type' => DeserializeFieldConverterFixture::class];
        $configuration = $this->createConfiguration('DeserializeField', 'foo', $options);
        $serializer = $this->createSerializer();
        $request = Request::create('/', 'POST', [], [], [], [], '{"bar1": "baz1"}');

        (new DeserializeFieldConverter($serializer))->apply($request, $configuration);
        $this->assertInstanceOf(DeserializeFieldConverterFixture::class, $request->attributes->get('foo'));
        $this->assertEquals('baz1', $request->attributes->get('foo')->bar1);
    }

    public function test_object_is_deserialized_with_different_group_and_applied_as_attribute(): void
    {
        $options = ['group' => 'test2', 'type' => DeserializeFieldConverterFixture::class];
        $configuration = $this->createConfiguration('DeserializeField', 'foo', $options);
        $serializer = $this->createSerializer();
        $request = Request::create('/', 'POST', [], [], [], [], '{"bar2": "baz2"}');

        (new DeserializeFieldConverter($serializer))->apply($request, $configuration);
        $this->assertInstanceOf(DeserializeFieldConverterFixture::class, $request->attributes->get('foo'));
        $this->assertEquals('baz2', $request->attributes->get('foo')->bar2);
    }

    public function test_object_is_not_set(): void
    {
        $configuration = $this->createConfiguration();
        $serializer = $this->createSerializer();
        $request = Request::create('/', 'GET');

        (new DeserializeFieldConverter($serializer))->apply($request, $configuration);
        $this->assertFalse($request->attributes->has('foo'));
    }

    public function test_object_is_deserialized_option_group_is_missing(): void
    {
        $options = ['type' => DeserializeFieldConverterFixture::class];
        $configuration = $this->createConfiguration('DeserializeField', 'foo', $options);
        $serializer = $this->createSerializer();
        $request = Request::create('/', 'POST');

        (new DeserializeFieldConverter($serializer))->apply($request, $configuration);
        $this->assertFalse($request->attributes->has('foo'));
    }

    public function test_object_is_deserialized_option_type_is_missing(): void
    {
        $options = ['group' => 'test'];
        $configuration = $this->createConfiguration('DeserializeField', 'foo', $options);
        $serializer = $this->createSerializer();
        $request = Request::create('/', 'POST');

        (new DeserializeFieldConverter($serializer))->apply($request, $configuration);
        $this->assertFalse($request->attributes->has('foo'));
    }
}
