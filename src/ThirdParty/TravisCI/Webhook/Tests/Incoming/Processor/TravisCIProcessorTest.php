<?php

namespace ThirdParty\TravisCI\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use ThirdParty\TravisCI\Webhook\Incoming\Processor\TravisCIProcessor;

class TravisCIProcessorTest extends TestCase
{
    public function providePayloads()
    {
        return [
            [
                file_get_contents(__DIR__.'/Fixtures/BuildPassed.json'),
                'J. Smith',
                'build.passed',
            ],
            [
                file_get_contents(__DIR__.'/Fixtures/BuildFailed.json'),
                'J. Smith',
                'build.failed',
            ],
        ];
    }

    public function test_set_get_name(): void
    {
        $processor = new TravisCIProcessor($name = 'travisci-processor');
        $this->assertEquals('travisci-processor', $processor->getName());
    }

    /** @dataProvider providePayloads */
    public function test_process_payload(
        string $contents,
        string $username,
        string $action
    ): void {
        $request = Request::create('/', 'POST');
        $request->request->set('payload', $contents);

        (new TravisCIProcessor('travisci-processor'))->process($request);
        $this->assertEquals($username, $request->request->get('username'));
        $this->assertEquals($action, $request->request->get('action'));
    }

    public function test_process_missing_payload(): void
    {
        $request = Request::create('/', 'POST');

        (new TravisCIProcessor('travisci-processor'))->process($request);
        $this->assertFalse($request->request->has('username'));
        $this->assertFalse($request->request->has('action'));
    }

    public function test_process_empty_payload(): void
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST');
        $request->request->set('payload', $contents);

        (new TravisCIProcessor('travisci-processor'))->process($request);
        $this->assertFalse($request->request->has('username'));
        $this->assertFalse($request->request->has('action'));
    }

    /** @expectedException InvalidArgumentException */
    public function test_process_broken_payload(): void
    {
        $contents = ',1%}';
        $request = Request::create('/', 'POST');
        $request->request->set('payload', $contents);

        (new TravisCIProcessor('travisci-processor'))->process($request);
    }
}
