<?php

namespace Yay\Bundle\EngineBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Yay\Bundle\EngineBundle\DependencyInjection\EngineExtension;
use Yay\Bundle\EngineBundle\EngineBundle;

class EngineBundleTest extends WebTestCase
{
    /**
     * @test
     */
    public function build()
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCompilerPass', 'registerForAutoconfiguration'])
            ->getMock();

        $builder->expects($this->once())
            ->method('addCompilerPass');

        $builder->expects($this->once())
            ->method('registerForAutoconfiguration')
            ->willReturn(new ChildDefinition(\stdClass::class));

        (new EngineBundle())->build($builder);
    }

    /**
     * @test
     */
    public function get_container_extension()
    {
        $this->assertInstanceOf(
            EngineExtension::class,
            (new EngineBundle())->getContainerExtension()
        );
    }
}
