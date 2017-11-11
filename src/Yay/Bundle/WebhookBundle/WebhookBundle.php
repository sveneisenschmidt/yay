<?php

namespace Yay\Bundle\WebhookBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\WebhookBundle\DependencyInjection\IncomingProcessorPass;
use Yay\Bundle\WebhookBundle\DependencyInjection\OutgoingProcessorPass;
use Yay\Bundle\WebhookBundle\DependencyInjection\WebhookExtension;
use Yay\Component\Webhook\Incoming\ProcessorInterface as IncomingProcessorInterface;
use Yay\Component\Webhook\Outgoing\ProcessorInterface as OutgoingProcessorInterface;

class WebhookBundle extends Bundle
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
