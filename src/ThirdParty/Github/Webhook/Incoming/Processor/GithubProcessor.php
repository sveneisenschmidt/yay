<?php

namespace ThirdParty\Github\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class GithubProcessor implements ProcessorInterface
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

        if ($request->headers->has('X-GitHub-Event')) {
            $action = $request->headers->get('X-GitHub-Event');
        }

        if ($data && isset($data['action']) && !empty($action)) {
            $action = sprintf('%s.%s', $action, $data['action']);
        }

        if ($data && isset($data['sender']['login'])) {
            $username = $data['sender']['login'];
        }

        if (!empty($action) && !empty($username)) {
            $request->request->set('action', $action);
            $request->request->set('username', $username);
        }
    }
}
