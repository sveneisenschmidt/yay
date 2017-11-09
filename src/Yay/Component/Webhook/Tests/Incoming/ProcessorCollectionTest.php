<?php

namespace Yay\Component\Webhook\Tests\Incoming;

use PHPUnit\Framework\TestCase;
use Yay\Component\Webhook\Incoming\ProcessorCollection;
use Yay\Component\Webhook\Incoming\ProcessorInterface;

class ProcessorCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function get_processor()
    {
        $processor1 = $this->createConfiguredMock(ProcessorInterface::class, [
            'getName' => $name1 = 'test-processor-01',
        ]);

        $processor2 = $this->createConfiguredMock(ProcessorInterface::class, [
            'getName' => $name2 = 'test-processor-02',
        ]);

        $collection = new ProcessorCollection();
        $collection->add($processor1);

        $this->assertEquals($processor1, $collection->getProcessor($name1));
        $this->assertNull($collection->getProcessor($name2));
    }

    /**
     * @test
     */
    public function has_processor()
    {
        $processor1 = $this->createConfiguredMock(ProcessorInterface::class, [
            'getName' => $name1 = 'test-processor-01',
        ]);

        $processor2 = $this->createConfiguredMock(ProcessorInterface::class, [
            'getName' => $name2 = 'test-processor-02',
        ]);

        $collection = new ProcessorCollection();
        $collection->add($processor1);

        $this->assertTrue($collection->hasProcessor($name1));
        $this->assertFalse($collection->hasProcessor($name2));
    }
}
