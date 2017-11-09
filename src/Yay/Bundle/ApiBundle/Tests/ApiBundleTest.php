<?php

namespace Yay\Bundle\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\ApiBundle\DependencyInjection\ApiExtension;
use Yay\Bundle\ApiBundle\ApiBundle;

class ApiBundleTest extends WebTestCase
{
    /**
     * @test
     */
    public function build()
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCompilerPass'])
            ->getMock();

        $builder->expects($this->once())
            ->method('addCompilerPass');

        (new ApiBundle())->build($builder);
    }

    /**
     * @test
     */
    public function get_container_extension()
    {
        $this->assertInstanceOf(
            ApiExtension::class,
            (new ApiBundle())->getContainerExtension()
        );
    }
}
