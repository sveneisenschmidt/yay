<?php

namespace App\Compat\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;

class CompatExtension extends Extension
{
    /**
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            $locator = new FileLocator(__DIR__.'/../Resources/config')
        );

        $files = [
            'services.yml',
            sprintf('services_%s.yml', $container->getParameter('kernel.environment')),
        ];

        foreach ($files as $file) {
            try {
                $loader->load($file);
            } catch (FileLocatorFileNotFoundException $e) {
            }
        }
    }
}
