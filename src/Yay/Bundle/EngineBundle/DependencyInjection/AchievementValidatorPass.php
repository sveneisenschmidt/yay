<?php

namespace Yay\Bundle\EngineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class AchievementValidatorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('yay.achievement_validation.validator_collection')) {
            return;
        }

        $definition = $container->findDefinition('yay.achievement_validation.validator_collection');
        $taggedServices = $container->findTaggedServiceIds('yay.achievement_validator');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('add', array(new Reference($id)));
        }
    }
}
