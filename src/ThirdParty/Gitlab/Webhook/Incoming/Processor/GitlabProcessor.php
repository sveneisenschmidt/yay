<?php

namespace ThirdParty\Gitlab\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Component\Webhook\Incoming\ProcessorInterface;

class GitlabProcessor implements ProcessorInterface
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

        $contents = $request->getContent(false);
        $data = json_decode($contents, true, 32);

        if (null === $data) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        switch ($request->headers->get('X-Gitlab-Event')) {
            case 'Push Hook':
                $this->processPushHook($request->request, $data);
                break;
            case 'Merge Request Hook':
                $this->processMergeRequestHook($request->request, $data);
                break;
            default:
                return;
        }
    }

    public function processPushHook(ParameterBag $request, array $data): void
    {
        $action = '';
        $username = '';

        if (isset($data['object_kind'])) {
            $action = sprintf('%s.created', $data['object_kind']);
        }

        if (isset($data['user_username'])) {
            $username = $data['user_username'];
        }

        $request->set('action', $action);
        $request->set('username', $username);
    }

    public function processMergeRequestHook(ParameterBag $request, array $data): void
    {
        $action = '';
        $username = '';

        if (isset($data['object_kind']) && isset($data['object_attributes']['state'])) {
            $action = sprintf('%s.%s', $data['object_kind'], $data['object_attributes']['state']);
        }

        if (isset($data['user']['username'])) {
            $username = $data['user']['username'];
        }

        $request->set('action', $action);
        $request->set('username', $username);
    }
}
