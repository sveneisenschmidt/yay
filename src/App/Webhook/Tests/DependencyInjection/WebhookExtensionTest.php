<?php

namespace App\Webhook\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Webhook\DependencyInjection\WebhookExtension;

class WebhookExtensionTest extends WebTestCase
{
    public function test_load(): void
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
