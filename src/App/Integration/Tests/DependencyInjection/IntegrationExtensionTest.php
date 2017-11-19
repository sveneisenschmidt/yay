<?php

namespace App\Integration\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Integration\DependencyInjection\IntegrationExtension;

class IntegrationExtensionTest extends WebTestCase
{
    public function test_load(): void
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getParameter'])
            ->getMock();

        $builder->expects($this->once())
            ->method('getParameter')
            ->willReturn('test');

        (new IntegrationExtension())->load([], $builder);
    }
}
