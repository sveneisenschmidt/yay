<?php

namespace Component\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class DummyProcessor implements ProcessorInterface
{
    /** @var string */
    protected $name;

    /** @var data */
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
            if (!$request->request->has($key, $value)) {
                $request->request->set($key, $value);
            }
        }
    }
}
