<?php

namespace Yay\Bundle\WebhookBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Yay\Bundle\WebhookBundle\DependencyInjection\WebhookExtension;
use Yay\Bundle\WebhookBundle\WebhookBundle;

class WebhookBundleTest extends WebTestCase
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

        $builder->expects($this->exactly(2))
            ->method('addCompilerPass');

        $builder->expects($this->exactly(2))
            ->method('registerForAutoconfiguration')
            ->willReturn(new ChildDefinition(\stdClass::class));

        (new WebhookBundle())->build($builder);
    }

    /**
     * @test
     */
    public function get_container_extension()
    {
        $this->assertInstanceOf(
            WebhookExtension::class,
            (new WebhookBundle())->getContainerExtension()
        );
    }
}
