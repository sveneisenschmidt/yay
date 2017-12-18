<?php

namespace Component\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;

trait EnvironmentConfigurationTrait
{
    public function loadFromDirectory(
        string $directory,
        array $configs,
        ContainerBuilder $container
    ): int {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator($directory)
        );

        $files = [
            'services.yml',
            sprintf('services_%s.yml', $container->getParameter('kernel.environment')),
        ];

        $loaded = 0;
        foreach ($files as $file) {
            try {
                $loader->load($file);
            } catch (FileLocatorFileNotFoundException $e) {
                continue;
            }

            ++$loaded;
        }

        return $loaded;
    }
}
