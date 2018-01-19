<?php

namespace ThirdParty\GitHub\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
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
        $data = json_decode($contents, true, 32);

        if (null === $data) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if ('push' === $event) {
            $this->processPushHook($request->request, $data);
        }

        if ('pull_request' === $event) {
            $this->processMergeRequestHook($request->request, $data);
        }
    }

    public function processPushHook(ParameterBag $request, array $data): void
    {
        $action = 'push';
        $username = ''; 

        if (isset($data['pusher']['name'])) {
            $username = $data['pusher']['name'];
        }

        $request->set('action', $action);
        $request->set('username', $username);
    }

    public function processMergeRequestHook(ParameterBag $request, array $data): void
    {
        $action = '';
        $username = '';

        if (isset($data['action'])) {
            $action = sprintf('pull_request.%s', $data['action']);
        }

        if (isset($data['pull_request']['user']['login'])) {
            $username = $data['pull_request']['user']['login'];
        }

        $request->set('action', $action);
        $request->set('username', $username);
    }
}
