<?php

namespace Yay\Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Yay\Component\Webhook\Incoming\Processor\NullProcessor;

class NullProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function does_not_modify_request()
    {
        $request1 = $this->createMock(Request::class);
        $request2 = clone $request1;

        $processor = new NullProcessor($name = 'null-processor');
        $processor->process($request1);

        $this->assertEquals($name, $processor->getName());
        $this->assertEquals($request1, $request2);
    }
}
