<?php

namespace Yay\Component\HttpFoundation\Test\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;
use Yay\Component\HttpFoundation\Request\ParamConverter\JsonFieldConverter;

class JsonFieldConverterTest extends TestCase
{
    /**
     * @param string $converterName
     * @param string $parameterName
     * @param array  $options
     *
     * @return ParamConverter
     */
    public function createConfiguration(
        string $converterName = 'JsonField',
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

    /**
     * @test
     */
    public function param_converter_is_supported(): void
    {
        $configuration = $this->createConfiguration();

        $this->assertTrue((new JsonFieldConverter())->supports($configuration));
    }

    /**
     * @test
     */
    public function param_converter_is_not_supported(): void
    {
        $configuration = $this->createConfiguration('JsonField2');

        $this->assertFalse((new JsonFieldConverter())->supports($configuration));
    }

    /**
     * @test
     */
    public function json_field_is_applied_as_attribute(): void
    {
        $configuration = $this->createConfiguration();
        $content = '{"foo": "bar"}';
        $request = Request::create('/', 'POST', [], [], [], [], $content);

        (new JsonFieldConverter())->apply($request, $configuration);
        $this->assertEquals('bar', $request->attributes->get('foo'));
    }

    /**
     * @test
     */
    public function json_field_is_applied_as_attribute_with_field(): void
    {
        $configuration = $this->createConfiguration('JsonField', 'foo', ['field' => 'baz']);
        $content = '{"baz": "bar"}';
        $request = Request::create('/', 'POST', [], [], [], [], $content);

        (new JsonFieldConverter())->apply($request, $configuration);
        $this->assertEquals('bar', $request->attributes->get('foo'));
    }

    /**
     * @test
     */
    public function json_field_is_not_set(): void
    {
        $configuration = $this->createConfiguration();
        $request = Request::create('/', 'POST');

        (new JsonFieldConverter())->apply($request, $configuration);
        $this->assertNull($request->attributes->get('foo'));
    }
}
