<?php

namespace Component\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class ChainProcessor implements ProcessorInterface
{
    /** @var string */
    protected $name;

    /** @var array|ProcessorInterface[] */
    protected $processors;

    public function __construct(string $name, array $processors = [])
    {
        $this->name = $name;
        $this->processors = $processors;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function process(Request $request): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($request);
        }
    }
}
