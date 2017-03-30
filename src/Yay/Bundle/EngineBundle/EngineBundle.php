<?php

namespace Yay\Bundle\EngineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Yay\Bundle\EngineBundle\DependencyInjection\EngineExtension;
use Yay\Bundle\EngineBundle\DependencyInjection\GoalValidatorPass;

class EngineBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GoalValidatorPass());
    }

    /**
     * @return EngineExtension
     */
    public function getContainerExtension()
    {
        return new EngineExtension();
    }
}
