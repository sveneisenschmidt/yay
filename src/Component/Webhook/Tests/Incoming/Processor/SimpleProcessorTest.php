<?php

namespace Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Component\Webhook\Incoming\Processor\SimpleProcessor;

class SimpleProcessorTest extends TestCase
{
    public function test_set_get_name(): void
    {
        $processor = new SimpleProcessor('test');
        $this->assertEquals('test', $processor->getName());
    }

    public function test_process_payload(): void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
          );
    }

    public function test_broken_payload(): void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
          );
    }

    public function test_empty_payload(): void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
          );
    }
}
