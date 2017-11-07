<?php

namespace Yay\Component\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Yay\Component\Webhook\Incoming\ProcessorInterface;

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

    public function process(Request $request): void
    {
    }
}
