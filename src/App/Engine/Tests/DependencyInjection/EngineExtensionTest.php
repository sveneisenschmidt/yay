<?php

namespace App\Engine\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Engine\DependencyInjection\EngineExtension;

class EngineExtensionTest extends WebTestCase
{
    public function test_load(): void
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getParameter'])
            ->getMock();

        $builder->expects($this->once())
            ->method('getParameter')
            ->willReturn('test');

        (new EngineExtension())->load([], $builder);
    }
}
