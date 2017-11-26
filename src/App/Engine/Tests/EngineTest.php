<?php

namespace App\Engine\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ChildDefinition;
use App\Engine\DependencyInjection\EngineExtension;
use App\Engine\Engine;

class EngineTest extends WebTestCase
{
    public function test_build(): void
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCompilerPass', 'registerForAutoconfiguration'])
            ->getMock();

        $builder->expects($this->exactly(2))
            ->method('addCompilerPass');

        $builder->expects($this->once())
            ->method('registerForAutoconfiguration')
            ->willReturn(new ChildDefinition(\stdClass::class));

        (new Engine())->build($builder);
    }

    public function test_get_container_extension(): void
    {
        $this->assertInstanceOf(
            EngineExtension::class,
            (new Engine())->getContainerExtension()
        );
    }
}
