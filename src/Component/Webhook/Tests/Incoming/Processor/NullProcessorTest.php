<?php

namespace Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\Processor\NullProcessor;

class NullProcessorTest extends TestCase
{
    public function test_set_get_name(): void
    {
        $processor = new NullProcessor('test');
        $this->assertEquals('test', $processor->getName());
    }

    public function test_does_not_modify_request(): void
    {
        $request1 = $this->createMock(Request::class);
        $request2 = clone $request1;

        $processor = new NullProcessor($name = 'null-processor');
        $processor->process($request1);

        $this->assertEquals($name, $processor->getName());
        $this->assertEquals($request1, $request2);
    }
}
