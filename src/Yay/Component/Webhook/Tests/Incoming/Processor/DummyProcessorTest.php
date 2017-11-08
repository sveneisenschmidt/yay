<?php

namespace Yay\Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Yay\Component\Webhook\Incoming\Processor\ProcessorInterface;
use Yay\Component\Webhook\Incoming\Processor\DummyProcessor;

class DummyProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function dummy_values_get_added()
    {
        $attributes = new ParameterBag();
        $request = $this->createMock(Request::class);
        $request->attributes = $attributes;

        $processor = new DummyProcessor($name = 'null-processor', [
            'player' => $player = 'test-player',
            'actions' => $actions = ['test-action'],
        ]);

        $this->assertFalse($attributes->has('player'));
        $this->assertFalse($attributes->has('actions'));

        $processor->process($request);

        $this->assertTrue($attributes->has('player'));
        $this->assertTrue($attributes->has('actions'));
        $this->assertEquals($player, $attributes->get('player'));
        $this->assertEquals($actions, $attributes->get('actions'));
    }
}
