<?php

namespace ThirdParty\CircleCI\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class CircleCIProcessor implements ProcessorInterface
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

        if (isset($payload['payload']['author_name'])) {
            $request->request->set('username', $payload['payload']['author_name']);
        }

        if (isset($payload['payload']['canceled']) && $payload['payload']['canceled'] === true) {
            $request->request->set('action', 'build.canceled');
        } elseif (isset($payload['payload']['outcome'])) {
            $request->request->set('action', sprintf('build.%s', $payload['payload']['outcome']));
        }
    }
}
