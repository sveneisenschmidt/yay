<?php

namespace Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Component\Webhook\Incoming\Processor\DummyProcessor;

class DummyProcessorTest extends TestCase
{
    public function test_set_get_name(): void
    {
        $processor = new DummyProcessor('test', []);
        $this->assertEquals('test', $processor->getName());
    }

    public function test_dummy_values_get_added(): void
    {
        $request = $this->createMock(Request::class);
        $request->request = new ParameterBag();

        $processor = new DummyProcessor($name = 'null-processor', [
            'player' => $player = 'test-player',
            'actions' => $actions = ['test-action'],
        ]);

        $this->assertFalse($request->request->has('player'));
        $this->assertFalse($request->request->has('actions'));

        $processor->process($request);

        $this->assertTrue($request->request->has('player'));
        $this->assertTrue($request->request->has('actions'));
        $this->assertEquals($player, $request->request->get('player'));
        $this->assertEquals($actions, $request->request->get('actions'));
    }
}
