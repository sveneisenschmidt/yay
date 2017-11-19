<?php

namespace App\Compat\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class JMSSerializerBundlePass implements CompilerPassInterface
{
    /**
     */
    public function process(ContainerBuilder $container)
    {
        $definitions = [
            'jms_serializer.doctrine_proxy_subscriber',
            'jms_serializer.stopwatch_subscriber',
            'jms_serializer.datetime_handler',
            'jms_serializer.array_collection_handler',
            'jms_serializer.constraint_violation_handler',
            'jms_serializer.metadata_driver',
        ];

        foreach ($definitions as $definition) {
            if ($container->hasDefinition($definition)) {
                $container->getDefinition($definition)->setPublic(true);
            }
        }
    }
}
