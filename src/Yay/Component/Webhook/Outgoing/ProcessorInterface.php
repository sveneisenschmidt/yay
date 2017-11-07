<?php

namespace Yay\Component\Webhook\Outgoing;

use Symfony\Component\HttpFoundation\Response;

interface ProcessorInterface
{
    public function getName(): string;

    public function process(Response $response): void;
}
