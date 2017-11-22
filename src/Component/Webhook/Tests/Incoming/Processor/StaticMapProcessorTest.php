<?php

namespace Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Component\Webhook\Incoming\Processor\StaticMapProcessor;

class StaticMapProcessorTest extends TestCase
{
    public function test_set_get_name(): void
    {
        $processor = new StaticMapProcessor('test', 'test', []);
        $this->assertEquals('test', $processor->getName());
    }

    public function test_does_replace_value(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag();
        $request->attributes->set($key = 'foo', 'bar');

        $processor = new StaticMapProcessor(
            $name = 'static-map-processor',
            $key,
            ['bar' => 'baz']
        );
        $processor->process($request);
        $this->assertEquals('baz', $request->attributes->get('foo'));
    }

    public function test_does_nothing(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag();

        $processor = new StaticMapProcessor(
            $name = 'static-map-processor',
            'foo',
            ['bar' => 'baz']
        );
        $processor->process($request);
        $this->assertFalse($request->attributes->has('foo'));
    }
}
