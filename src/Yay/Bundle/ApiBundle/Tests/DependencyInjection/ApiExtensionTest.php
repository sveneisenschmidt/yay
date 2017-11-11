<?php

namespace Yay\Bundle\ApiBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\ApiBundle\DependencyInjection\ApiExtension;

class ApiExtensionTest extends WebTestCase
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

        (new ApiExtension())->load([], $builder);
    }
}
