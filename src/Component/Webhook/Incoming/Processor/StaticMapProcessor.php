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
        if (!$request->request->has($this->key)) {
            return;
        }

        $value = $request->request->get($this->key);
        foreach ($this->map as $mapKey => $mapValue) {
            if ($value == $mapKey) {
                $request->request->set($this->key, $mapValue);
            }
        }
    }
}
