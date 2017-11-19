<?php

namespace Component\Webhook\Outgoing\Processor;

use Symfony\Component\HttpFoundation\Response;
use Component\Webhook\Outgoing\ProcessorInterface;

class NullProcessor implements ProcessorInterface
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

    public function process(Response $response): void
    {
    }
}
