<?php

namespace ThirdParty\Github\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use ThirdParty\Github\Webhook\Incoming\Processor\GithubProcessor;

class GithubProcessorTest extends TestCase
{
    /**
     * @test
     */
    public function set_get_name()
    {
        $processor = new GithubProcessor($name = 'github-processor');
        $this->assertEquals('github-processor', $processor->getName());
    }

    /**
     * @test
     */
    public function process_payload()
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

    /**
     * @test
     */
    public function process_empty_payload_but_event()
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new GithubProcessor('github-processor'))->process($request);
        $this->assertFalse($request->attributes->has('username'));
        $this->assertFalse($request->attributes->has('action'));
    }

    /**
     * @test
     */
    public function process_empty_payload()
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);
        $request->headers->set('X-GitHub-Event', 'github-event');

        (new GithubProcessor('github-processor'))->process($request);
        $this->assertFalse($request->attributes->has('username'));
        $this->assertFalse($request->attributes->has('action'));
    }
}
