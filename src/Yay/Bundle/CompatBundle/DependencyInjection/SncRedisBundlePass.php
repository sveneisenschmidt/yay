<?php

namespace Yay\Bundle\CompatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class SncRedisBundlePass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definitions = [
            'snc_redis.client.class',
        ];

        foreach ($definitions as $definition) {
            if ($container->hasDefinition($definition)) {
                $container->getDefinition($definition)->setPublic(true);
            }
        }
    }
}
