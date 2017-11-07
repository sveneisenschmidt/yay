<?php

namespace Yay\Component\Webhook\Incoming;

use Doctrine\Common\Collections\ArrayCollection;
use Yay\Component\Webhook\Incoming\ProcessorInterface;

class ProcessorCollection extends ArrayCollection
{
    public function hasProcessor(string $name): bool
    {
        return (bool) $this->getProcessor($name);
    }

    public function getProcessor(string $name): ?ProcessorInterface
    {
        $matches = $this->filter(function (ProcessorInterface $processor) use ($name) {
            return $processor->getName() === $name;
        });

        $processor = $matches->first();
        return !!$processor ? $processor : null;
    }
}
