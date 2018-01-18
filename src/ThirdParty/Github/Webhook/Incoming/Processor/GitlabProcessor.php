<?php

namespace ThirdParty\Github\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
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
        $action = '';
        $username = '';

        $contents = $request->getContent(false);
        $data = json_decode($contents, true, 32);

        if (null === $data) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if ($request->headers->has('X-Gitlab-Event')) {
            $action = strtolower($request->headers->get('X-Gitlab-Event'));
            $action = preg_replace('/[^a-z0-9]+/', '_', $action);
        }

        if ($data && isset($data['object_kind']) && !empty($action)) {
            $action = sprintf('%s.%s', $action, $data['object_kind']);
        }

        if ($data && isset($data['user_username'])) {
            $username = $data['user_username'];
        }

        if ($data && isset($data['user']['username'])) {
            $username = $data['user']['username'];
        }

        var_dump($action);
        var_dump($username);
        die();

        if (!empty($action) && !empty($username)) {
            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }
    }
}
