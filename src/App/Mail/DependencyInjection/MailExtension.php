<?php

namespace App\Mail\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Component\DependencyInjection\EnvironmentConfigurationTrait;

class MailExtension extends Extension
{
    use EnvironmentConfigurationTrait;
    
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->loadFromDirectory(__DIR__.'/../Resources/config', $configs, $container);
    }
}
