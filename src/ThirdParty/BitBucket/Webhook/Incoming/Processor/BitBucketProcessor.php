<?php

namespace ThirdParty\BitBucket\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Component\Webhook\Incoming\ProcessorInterface;

class BitBucketProcessor implements ProcessorInterface
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
        if (!$request->headers->has('X-Event-Key')) {
            return;
        }

        $event = $request->headers->get('X-Event-Key');
        $contents = $request->getContent(false);
        $payload = json_decode($contents, true, 32);

        if (null === $payload) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if ('repo:push' === $event) {
            list ($action, $username) = $this->processPushHook($event, $payload);

            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }

        if (preg_match('/^pullrequest:/', $event)) {
            list ($action, $username) = $this->processMergeRequestHook($event, $payload);

            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }
    }

    public function processPushHook(string $event, array $payload): array
    {
        $action = 'push';
        $username = '';

        if (isset($payload['actor']['username'])) {
            $username = $payload['actor']['username'];
        }

        return [$action, $username];
    }

    public function processMergeRequestHook(string $event, array $payload): array
    {
        $action = '';
        $username = '';

        if (isset($payload['actor']['username'])) {
            $username = $payload['actor']['username'];
        }

        if (preg_match('/^pullrequest:(?P<action>[A-Za-z]+)$/', $event, $matches) > 0) {
            $action = sprintf('pull_request.%s', $matches['action']);
        }

        return [$action, $username];
    }
}
