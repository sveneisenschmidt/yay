<?php

namespace App\Engine;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Engine\DependencyInjection\AchievementValidatorPass;
use App\Engine\DependencyInjection\EngineExtension;
use Component\Engine\AchievementValidatorInterface;
use Component\Engine\EventListener\EventListenerInterface;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class Engine extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterListenersPass('event_dispatcher', 'yay.event_listener', 'yay.event_subscriber'));        
        $container->addCompilerPass(new AchievementValidatorPass());
        $container->registerForAutoconfiguration(AchievementValidatorInterface::class)
            ->addTag('yay.achievement_validator');

    }

    public function getContainerExtension(): EngineExtension
    {
        return new EngineExtension();
    }
}
