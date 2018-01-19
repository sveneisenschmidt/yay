<?php

namespace ThirdParty\GitHub\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use ThirdParty\GitHub\Webhook\Incoming\Processor\GitHubProcessor;

class GitHubProcessorTest extends TestCase
{
    public function providePayloads()
    {
        return [
            [
                'push',
                file_get_contents(__DIR__.'/Fixtures/PushWebhook.json'),
                'baxterthehacker',
                'push',
            ],
            [
                'pull_request',
                file_get_contents(__DIR__.'/Fixtures/PullRequestWebhook.json'),
                'baxterthehacker',
                'pull_request.opened',
            ],
        ];
    }

    public function test_set_get_name(): void
    {
        $processor = new GitHubProcessor($name = 'github-processor');
        $this->assertEquals('github-processor', $processor->getName());
    }

    /** @dataProvider providePayloads */
    public function test_process_payload(
        string $header,
        string $contents,
        string $username,
        string $action
    ): void {
        $request = Request::create('/', 'POST', [], [], [], [], $contents);
        $request->headers->set('X-GitHub-Event', $header);

        (new GitHubProcessor('github-processor'))->process($request);
        $this->assertEquals($username, $request->request->get('username'));
        $this->assertEquals($action, $request->request->get('action'));
    }

    public function test_process_empty_payload_but_event(): void
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new GitHubProcessor('github-processor'))->process($request);
        $this->assertFalse($request->request->has('username'));
        $this->assertFalse($request->request->has('action'));
    }

    public function test_process_empty_payload(): void
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);
        $request->headers->set('X-GitHub-Event', 'github-event');

        (new GitHubProcessor('github-processor'))->process($request);
        $this->assertFalse($request->request->has('username'));
        $this->assertFalse($request->request->has('action'));
    }

    /** @expectedException InvalidArgumentException */
    public function test_process_broken_payload(): void
    {
        $contents = ',1%}';
        $request = Request::create('/', 'POST', [], [], [], [], $contents);
        $request->headers->set('X-GitHub-Event', 'github-event');

        (new GitHubProcessor('github-processor'))->process($request);
    }
}
