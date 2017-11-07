<?php

namespace Yay\Bundle\WebhookBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Yay\Component\Webhook\Incoming\ProcessorInterface;
use Yay\Component\Webhook\Incoming\ProcessorCollection;

class IncomingProcessorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition(ProcessorCollection::class);
        $taggedServices = $container->findTaggedServiceIds('yay.webhook_incoming');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('add', array(new Reference($id)));
        }
    }
}
