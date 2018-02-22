<?php

namespace Component\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class SimpleProcessor implements ProcessorInterface
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
        $contents = $request->getContent(false);
        $payload = json_decode($contents, true, 32);

        if (null === $payload) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if (isset($payload['username'])) {
            $request->request->set('username', $payload['username']);
        }

        if (isset($payload['action'])) {
            $request->request->set('action', $payload['action']);
        }
    }
}
