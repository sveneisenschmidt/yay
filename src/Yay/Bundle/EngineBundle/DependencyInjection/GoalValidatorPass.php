<?php

namespace Yay\Bundle\EngineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class GoalValidatorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('yay.goal_validation.validator_collection')) {
            return;
        }

        $definition = $container->findDefinition('yay.goal_validation.validator_collection');
        $taggedServices = $container->findTaggedServiceIds('yay.goal_validator');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('add', array(new Reference($id)));
        }
    }
}
