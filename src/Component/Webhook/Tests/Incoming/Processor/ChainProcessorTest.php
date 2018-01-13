<?php

namespace Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;
use Component\Webhook\Incoming\Processor\ChainProcessor;

class ChainProcessorTest extends TestCase
{
    public function test_set_get_name(): void
    {
        $processor = new ChainProcessor('test', []);
        $this->assertEquals('test', $processor->getName());
    }

    public function test_chain_processor_executes_all_processors(): void
    {
        $processor1 = $this->getMockBuilder(ProcessorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['process', 'getName'])
            ->getMock();
        $processor1->expects($this->once())
            ->method('process');

        $processor2 = $this->getMockBuilder(ProcessorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['process', 'getName'])
            ->getMock();
        $processor2->expects($this->once())
            ->method('process');

        $processor3 = $this->getMockBuilder(ProcessorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['process', 'getName'])
            ->getMock();
        $processor3->expects($this->once())
            ->method('process');

        $request = $this->createMock(Request::class);
        $processors = [$processor1, $processor2, $processor3];
        (new ChainProcessor('chain-processor', $processors))->process($request);
    }
}
