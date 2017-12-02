<?php

namespace App\Integration\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Component\Webhook\Incoming\Processor\ChainProcessor;

class IncomingChainProcessorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('yay.webhook_incoming.processor');
        foreach ($services as $id => $tags) {
            $definition = $container->findDefinition($id);
            if ($definition && ChainProcessor::class == $definition->getClass()) {
                $this->processChainProcessor($container, $definition);
            }
        }
    }

    public function processChainProcessor(
        ContainerBuilder $container,
        Definition $definition
    ): void {
        $arguments = $definition->getArguments();
        if (count($arguments) < 2) {
            return;
        }

        $argument = $definition->getArgument(1);
        if (!is_array($argument)) {
            return;
        }

        foreach ($argument as $index => $id) {
            $argument[$index] = is_string($id) ? new Reference($id) : $id;
        }

        $definition->setArgument(1, $argument);
    }
}
