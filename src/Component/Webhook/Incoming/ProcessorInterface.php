<?php

namespace Component\Webhook\Incoming;

use Symfony\Component\HttpFoundation\Request;

interface ProcessorInterface
{
    public function getName(): string;

    public function process(Request $request): void;
}
