<?php

namespace App\Api\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Api\DependencyInjection\ApiExtension;
use App\Api\Api;

class ApiTest extends WebTestCase
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

        (new Api())->build($builder);
    }

    /**
     * @test
     */
    public function get_container_extension()
    {
        $this->assertInstanceOf(
            ApiExtension::class,
            (new Api())->getContainerExtension()
        );
    }
}
