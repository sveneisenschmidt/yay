<?php

namespace App\Compat\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Component\DependencyInjection\EnvironmentConfigurationTrait;

class CompatExtension extends Extension
{
    use EnvironmentConfigurationTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $this->loadFromDirectory(__DIR__.'/../Resources/config', $configs, $container);
    }
}
