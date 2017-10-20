<?php
namespace Yay\Component\HttpFoundation\Test\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;
use Yay\Component\HttpFoundation\Request\ParamConverter\QueryStringConverter;


class QueryStringConverterTest extends TestCase
{
    /**
     * @param string    $converterName
     * @param string    $parameterName
     *
     * @return ParamConverter
     */
    public function createConfiguration(string $converterName, string $parameterName): ParamConverter
    {
        $configuration = $this->getMockBuilder(ParamConverter::class)
                              ->disableOriginalConstructor()
                              ->setMethods(['getConverter', 'getName'])
                              ->getMock();

        $configuration->method('getConverter')
                      ->willReturn($converterName);

        $configuration->method('getName')
                      ->willReturn($parameterName);

        return $configuration;
    }

    /**
     * @test
     */
    public function param_converter_is_supported()
    {
        $configuration = $this->createConfiguration('QueryString', 'test');

        $this->assertTrue((new QueryStringConverter())->supports($configuration));
    }

    /**
     * @test
     */
    public function param_converter_is_not_supported()
    {
        $configuration = $this->createConfiguration('QueryString2', 'test');

        $this->assertFalse((new QueryStringConverter())->supports($configuration));
    }

    /**
     * @test
     */
    public function query_parameter_is_applied_as_attribute()
    {
        $configuration = $this->createConfiguration('QueryString', 'key');
        $request = Request::create('/', 'GET', ['key' => 'value']);

        (new QueryStringConverter())->apply($request, $configuration);
        $this->assertEquals('value', $request->attributes->get('key'));
    }

    /**
     * @test
     */
    public function query_parameter_is_not_set()
    {
        $configuration = $this->createConfiguration('QueryString', 'key');
        $request = Request::create('/', 'GET', []);

        (new QueryStringConverter())->apply($request, $configuration);
        $this->assertNull($request->attributes->get('key'));
    }
}
