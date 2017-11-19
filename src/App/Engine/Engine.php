<?php

namespace App\Engine;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Engine\DependencyInjection\AchievementValidatorPass;
use App\Engine\DependencyInjection\EngineExtension;
use Component\Engine\AchievementValidatorInterface;

class Engine extends Bundle
{
    /**
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AchievementValidatorPass());
        $container->registerForAutoconfiguration(AchievementValidatorInterface::class)
            ->addTag('yay.achievement_validator');
    }

    public function getContainerExtension(): EngineExtension
    {
        return new EngineExtension();
    }
}
