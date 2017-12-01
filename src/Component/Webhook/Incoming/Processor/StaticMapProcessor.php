<?php

namespace Component\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Component\Webhook\Incoming\ProcessorInterface;

class StaticMapProcessor implements ProcessorInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $key;

    /** @var array */
    protected $map;

    public function __construct(string $name, string $key, array $map)
    {
        $this->name = $name;
        $this->key = $key;
        $this->map = $map;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function process(Request $request): void
    {
        if (!$request->attributes->has($this->key)) {
            return;
        }

        $value = $request->attributes->get($this->key);

        foreach ($this->map as $map) {
            if ($value == key($map)) {
                $request->attributes->set($this->key, current($map));
            }
        }
    }
}
