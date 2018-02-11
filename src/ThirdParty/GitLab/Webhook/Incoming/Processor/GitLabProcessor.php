<?php

namespace ThirdParty\GitLab\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class GitLabProcessor implements ProcessorInterface
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
        if (!$request->headers->has('X-Gitlab-Event')) {
            return;
        }

        $event = $request->headers->get('X-Gitlab-Event');
        $contents = $request->getContent(false);
        $payload = json_decode($contents, true, 32);

        if (null === $payload) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if ('Push Hook' === $event) {
            list($action, $username) = $this->processPushHook($event, $payload);

            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }

        if ('Merge Request Hook' === $event) {
            list($action, $username) = $this->processMergeRequestHook($event, $payload);

            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }
    }

    public function processPushHook(string $event, array $payload): array
    {
        $action = 'push';
        $username = '';

        if (isset($payload['user_username'])) {
            $username = $payload['user_username'];
        }

        return [$action, $username];
    }

    public function processMergeRequestHook(string $event, array $payload): array
    {
        $action = '';
        $username = '';

        if (isset($payload['object_kind']) && isset($payload['object_attributes']['state'])) {
            $action = sprintf('%s.%s', $payload['object_kind'], $payload['object_attributes']['state']);
        }

        if (isset($payload['user']['username'])) {
            $username = $payload['user']['username'];
        }

        return [$action, $username];
    }
}
