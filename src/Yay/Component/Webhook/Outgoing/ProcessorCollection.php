<?php

namespace Yay\Component\Webhook\Outgoing;

use Doctrine\Common\Collections\ArrayCollection;

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

        return (bool) $processor ? $processor : null;
    }
}
