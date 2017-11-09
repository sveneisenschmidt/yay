<?php

namespace Yay\Bundle\WebhookBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\WebhookBundle\DependencyInjection\WebhookExtension;

class WebhookExtensionTest extends WebTestCase
{
    /**
     * @test
     */
    public function load()
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getParameter'])
            ->getMock();

        $builder->expects($this->once())
            ->method('getParameter')
            ->willReturn('test');

        (new WebhookExtension())->load([], $builder);
    }
}
