<?php

namespace Component\DependencyInjection\tTests;

use PHPUnit\Framework\TestCase;
use Component\DependencyInjection\EnvironmentConfigurationTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnvironmentConfigurationTraitTest extends TestCase
{
    public function createInstance(): object
    {
        return new class() {
            use EnvironmentConfigurationTrait;
        };
    }

    public function test_load_from_directory()
    {
        $configs = [];
        $container = $this->createConfiguredMock(ContainerBuilder::class, [
            'getParameter' => 'test',
        ]);

        $instance = $this->createInstance();
        $loaded = $instance->loadFromDirectory(
            __DIR__.'/Fixtures/EnvironmentConfigurationTrait',
            $configs,
            $container
        );

        $this->assertEquals(2, $loaded);
    }

    public function test_load_from_directory_but_different_environment()
    {
        $configs = [];
        $container = $this->createConfiguredMock(ContainerBuilder::class, [
            'getParameter' => 'dev',
        ]);

        $instance = $this->createInstance();
        $loaded = $instance->loadFromDirectory(
            __DIR__.'/Fixtures/EnvironmentConfigurationTrait',
            $configs,
            $container
        );

        $this->assertEquals(1, $loaded);
    }
}
