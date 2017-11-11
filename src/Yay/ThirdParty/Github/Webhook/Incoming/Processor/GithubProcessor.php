<?php

namespace Yay\ThirdParty\Github\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Yay\Component\Webhook\Incoming\ProcessorInterface;

class GithubProcessor implements ProcessorInterface
{
    /* @var string */
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

        $contents = $request->getContent(false);
        $data = json_decode($contents, true, 32);

        if ($data && isset($data['action'])) {
            $request->attributes->set('action', sprintf(
                '%s.%s', 
                $request->headers->get('X-GitHub-Event'), 
                $data['action']
            ));
        }

        if ($data && isset($data['sender']['login'])) {
            $request->attributes->set('username', $data['sender']['login']);
        }
    }
}
