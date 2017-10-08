<?php

namespace Yay\Bundle\EngineBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Yay\Bundle\EngineBundle\DependencyInjection\AchievementValidatorPass;
use Yay\Bundle\EngineBundle\DependencyInjection\EngineExtension;
use Yay\Component\Engine\AchievementValidatorInterface;

class EngineBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AchievementValidatorPass());
        $container->registerForAutoconfiguration(AchievementValidatorInterface::class)
            ->addTag('yay.achievement_validator');
    }

    /**
     * @return EngineExtension
     */
    public function getContainerExtension()
    {
        return new EngineExtension();
    }
}
