<?php

namespace App\Webhook\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Component\Webhook\Outgoing\ProcessorCollection;

class OutgoingProcessorPass implements CompilerPassInterface
{
    /**
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition(ProcessorCollection::class);
        $taggedServices = $container->findTaggedServiceIds('yay.webhook_outgoing');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('add', array(new Reference($id)));
        }
    }
}
