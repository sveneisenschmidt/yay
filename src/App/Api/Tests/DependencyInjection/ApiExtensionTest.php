<?php

namespace App\Api\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Api\DependencyInjection\ApiExtension;

class ApiExtensionTest extends WebTestCase
{
    public function test_load(): void
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getParameter'])
            ->getMock();

        $builder->expects($this->once())
            ->method('getParameter')
            ->willReturn('test');

        (new ApiExtension())->load([], $builder);
    }
}
