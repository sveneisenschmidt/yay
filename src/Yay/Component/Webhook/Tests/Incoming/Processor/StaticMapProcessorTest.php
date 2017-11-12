<?php

namespace Yay\Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Yay\Component\Webhook\Incoming\Processor\StaticMapProcessor;

class StaticMapProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function set_get_name()
    {
        $processor = new StaticMapProcessor('test', 'test', []);
        $this->assertEquals('test', $processor->getName());
    }

    /**
     * @test
     */
    public function does_replace_value()
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag();
        $request->attributes->set($key = 'foo', 'bar');

        $processor = new StaticMapProcessor(
            $name = 'static-map-processor',
            $key,
            [ 'bar' => 'baz']
        );
        $processor->process($request);
        $this->assertEquals('baz', $request->attributes->get('foo'));
    }

    /**
     * @test
     */
    public function does_nothing()
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag();

        $processor = new StaticMapProcessor(
            $name = 'static-map-processor',
            'foo',
            [ 'bar' => 'baz']
        );
        $processor->process($request);
        $this->assertFalse($request->attributes->has('foo'));
    }
}
