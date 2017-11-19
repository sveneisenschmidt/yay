<?php

namespace App\Webhook;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Webhook\DependencyInjection\IncomingProcessorPass;
use App\Webhook\DependencyInjection\OutgoingProcessorPass;
use App\Webhook\DependencyInjection\WebhookExtension;
use Component\Webhook\Incoming\ProcessorInterface as IncomingProcessorInterface;
use Component\Webhook\Outgoing\ProcessorInterface as OutgoingProcessorInterface;

class Webhook extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new IncomingProcessorPass());
        $container->registerForAutoconfiguration(IncomingProcessorInterface::class)
            ->addTag('yay.webhook_incoming');

        $container->addCompilerPass(new OutgoingProcessorPass());
        $container->registerForAutoconfiguration(OutgoingProcessorInterface::class)
            ->addTag('yay.webhook_outgoing');
    }

    /**
     * @return WebhookExtension
     */
    public function getContainerExtension()
    {
        return new WebhookExtension();
    }
}
