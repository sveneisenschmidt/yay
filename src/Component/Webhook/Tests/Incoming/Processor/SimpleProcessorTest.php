<?php

namespace Component\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Component\Webhook\Incoming\Processor\SimpleProcessor;

class SimpleProcessorTest extends TestCase
{
    public function providePayloads()
    {
        return [
            [
                file_get_contents(__DIR__.'/Fixtures/SimplePushAction.json'),
                'Alex Doe',
                'simple.push',
            ],
            [
                file_get_contents(__DIR__.'/Fixtures/SimplePullAction.json'),
                'Alex Doe',
                'simple.pull',
            ],
        ];
    }

    public function test_set_get_name(): void
    {
        $processor = new SimpleProcessor('test');
        $this->assertEquals('test', $processor->getName());
    }

    /** @dataProvider providePayloads */
    public function test_process_payload(
        string $contents,
        string $username,
        string $action
    ): void {
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new SimpleProcessor('simple-processor'))->process($request);
        $this->assertEquals($username, $request->request->get('username'));
        $this->assertEquals($action, $request->request->get('action'));
    }

    /** @expectedException InvalidArgumentException */
    public function test_broken_payload(): void
    {
        $contents = ',1%}';
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new SimpleProcessor('simple-processor'))->process($request);
    }

    public function test_empty_payload(): void
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new SimpleProcessor('simple-processor'))->process($request);
        $this->assertFalse($request->request->has('username'));
        $this->assertFalse($request->request->has('action'));
    }
}
