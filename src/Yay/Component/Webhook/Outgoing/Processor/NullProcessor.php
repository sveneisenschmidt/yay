<?php

namespace Yay\Component\Webhook\Outgoing\Processor;

use Symfony\Component\HttpFoundation\Response;
use Yay\Component\Webhook\Outgoing\ProcessorInterface;

class NullProcessor implements ProcessorInterface
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

    public function process(Response $response): void
    {
    }
}
