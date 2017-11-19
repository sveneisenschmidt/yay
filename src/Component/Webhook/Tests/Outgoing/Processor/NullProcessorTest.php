<?php

namespace Component\Webhook\Tests\Outgoing\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Component\Webhook\Outgoing\Processor\NullProcessor;

class NullProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function does_not_modify_response()
    {
        $response1 = $this->createMock(Response::class);
        $response2 = clone $response1;

        $processor = new NullProcessor($name = 'null-processor');
        $processor->process($response1);

        $this->assertEquals($name, $processor->getName());
        $this->assertEquals($response1, $response2);
    }
}
