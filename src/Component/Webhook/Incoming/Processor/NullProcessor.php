<?php

namespace Component\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

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

    public function process(Request $request): void
    {
    }
}
