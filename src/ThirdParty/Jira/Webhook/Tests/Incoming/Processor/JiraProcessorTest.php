<?php

namespace ThirdParty\Jira\Webhook\Tests\Incoming\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use ThirdParty\Jira\Webhook\Incoming\Processor\JiraProcessor;

class JiraProcessorTest extends TestCase
{
    public function providePayloads()
    {
        return [
            [
                file_get_contents(__DIR__.'/Fixtures/IssueCreated.json'),
                'sveneisenschmidt',
                'jira.issue_created',
                [],
            ],
            [
                file_get_contents(__DIR__.'/Fixtures/IssueUpdated.json'),
                'sveneisenschmidt',
                'jira.issue_updated',
                [],
            ],
            [
                file_get_contents(__DIR__.'/Fixtures/IssueClosed.json'),
                'sveneisenschmidt',
                'jira.issue_updated',
            ],
            [
                file_get_contents(__DIR__.'/Fixtures/IssueClosed.json'),
                'sveneisenschmidt',
                'jira.issue_closed',
                [
                    'jira.issue_closed' => "issue['fields']['resolution'] != null",
                ],
            ],
        ];
    }

    public function test_set_get_name(): void
    {
        $processor = new JiraProcessor($name = 'jira-processor');
        $this->assertEquals('jira-processor', $processor->getName());
    }

    /** @dataProvider providePayloads */
    public function test_process_payload(
        string $contents,
        string $username,
        string $action,
        array $map = []
    ): void {
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new JiraProcessor('jira-processor', $map))->process($request);
        $this->assertEquals($username, $request->request->get('username'));
        $this->assertEquals($action, $request->request->get('action'));
    }

    public function test_process_empty_payload(): void
    {
        $contents = json_encode([]);
        $request = Request::create('/', 'POST', [], [], [], [], $contents);

        (new JiraProcessor('jira-processor'))->process($request);
        $this->assertFalse($request->request->has('username'));
        $this->assertFalse($request->request->has('action'));
    }

    /** @expectedException InvalidArgumentException */
    public function test_process_broken_payload(): void
    {
        $contents = ',1%}';
        $request = Request::create('/', 'POST');
        $request->request->set('payload', $contents);

        (new JiraProcessor('jira-processor'))->process($request);
    }
}
