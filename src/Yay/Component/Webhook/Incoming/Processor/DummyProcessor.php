<?php

namespace Yay\Component\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Yay\Component\Webhook\Incoming\ProcessorInterface;

class DummyProcessor implements ProcessorInterface
{
    /* @var string */
    protected $name;

    /* @var data */
    protected $data;

    public function __construct(string $name, array $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function process(Request $request): void
    {
        foreach ($this->data as $key => $value) {
            if (!$request->attributes->has($key, $value)) {
                $request->attributes->set($key, $value);
            }
        }
    }
}
