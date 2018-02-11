<?php

namespace ThirdParty\TravisCI\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class TravisCIProcessor implements ProcessorInterface
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
        if (!$request->request->has('payload')) {
            return;
        }

        $contents = $request->request->get('payload');
        $payload = json_decode($contents, true, 32);

        if (null === $payload) {
            throw new \InvalidArgumentException('Could not decode json payload.');
        }

        if (isset($payload['state'])) {
            $request->request->set('action', sprintf('build.%s', $payload['state']));
        }

        if (isset($payload['author_name'])) {
            $request->request->set('username', $payload['author_name']);
        }
    }
}
