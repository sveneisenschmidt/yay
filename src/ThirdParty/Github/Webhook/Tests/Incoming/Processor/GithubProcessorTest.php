<?php

namespace ThirdParty\Github\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use ThirdParty\Github\Webhook\Incoming\Processor\GithubProcessor;

class GithubProcessorTest extends TestCase
{
    public function test_set_get_name(): void
    {
        $processor = new GithubProcessor($name = 'github-processor');
        $this->assertEquals('github-processor', $processor->getName());
    }

    public function test_process_payload(): void
    {
        $contents = json_encode([
            'action' => 'github-action',
            'sender' => ['login' => 'octocat'],
        ]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);
        $request->headers->set('X-GitHub-Event', 'github-event');

        (new GithubProcessor('github-processor'))->process($request);
        $this->assertEquals('octocat', $request->attributes->get('username'));
        $this->assertEquals('github-event.github-action', $request->attributes->get('action'));
    }

    public function test_process_empty_payload_but_event(): void
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new GithubProcessor('github-processor'))->process($request);
        $this->assertFalse($request->attributes->has('username'));
        $this->assertFalse($request->attributes->has('action'));
    }

    public function test_process_empty_payload(): void
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);
        $request->headers->set('X-GitHub-Event', 'github-event');

        (new GithubProcessor('github-processor'))->process($request);
        $this->assertFalse($request->attributes->has('username'));
        $this->assertFalse($request->attributes->has('action'));
    }
}
