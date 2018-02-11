<?php

namespace ThirdParty\GitHub\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class GitHubProcessor implements ProcessorInterface
{
    /** @var string */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function process(Request $request): void
    {
        if (!$request->headers->has('X-GitHub-Event')) {
            return;
        }

        $event = $request->headers->get('X-GitHub-Event');
        $contents = $request->getContent(false);
        $payload = json_decode($contents, true, 32);

        if (null === $payload) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if ('push' === $event) {
            list($action, $username) = $this->processPushHook($event, $payload);

            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }

        if ('pull_request' === $event) {
            list($action, $username) = $this->processMergeRequestHook($event, $payload);

            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }
    }

    public function processPushHook(string $event, array $payload): array
    {
        $action = 'push';
        $username = '';

        if (isset($payload['pusher']['name'])) {
            $username = $payload['pusher']['name'];
        }

        return [$action, $username];
    }

    public function processMergeRequestHook(string $event, array $payload): array
    {
        $action = '';
        $username = '';

        if (isset($payload['action'])) {
            if ('closed' === $payload['action'] && isset($payload['pull_request']['merged'])) {
                $action = sprintf('pull_request.%s', $payload['pull_request']['merged'] ? 'merged' : 'closed');
            } else {
                $action = sprintf('pull_request.%s', $payload['action']);
            }
        }

        if (isset($payload['pull_request']['user']['login'])) {
            $username = $payload['pull_request']['user']['login'];
        }

        return [$action, $username];
    }
}
