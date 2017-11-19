<?php

namespace Component\Webhook\Tests;

use PHPUnit\Framework\TestCase;
use Component\Webhook\Webhook;
use Component\Webhook\Incoming\ProcessorCollection as IncomingProcessorCollection;
use Component\Webhook\Outgoing\ProcessorCollection as OutgoingProcessorCollection;

class WebhookTest extends TestCase
{
    /**
     * @test
     */
    public function pass_collectors()
    {
        $collection1 = new IncomingProcessorCollection();
        $collection2 = new OutgoingProcessorCollection();

        $webhook = new Webhook($collection1, $collection2);
        $this->assertSame($collection1, $webhook->getIncomingProcessors());
        $this->assertSame($collection2, $webhook->getOutgoingProcessors());
    }
}
