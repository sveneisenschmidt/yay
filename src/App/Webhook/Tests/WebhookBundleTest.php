<?php

namespace App\Webhook\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ChildDefinition;
use App\Webhook\DependencyInjection\WebhookExtension;
use App\Webhook\Webhook;

class WebhookTest extends WebTestCase
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

        (new Webhook())->build($builder);
    }

    /**
     * @test
     */
    public function get_container_extension()
    {
        $this->assertInstanceOf(
            WebhookExtension::class,
            (new Webhook())->getContainerExtension()
        );
    }
}
