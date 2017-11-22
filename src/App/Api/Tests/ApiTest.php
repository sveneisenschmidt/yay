<?php

namespace App\Api\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Api\DependencyInjection\ApiExtension;
use App\Api\Api;

class ApiTest extends WebTestCase
{
    public function test_build(): void
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCompilerPass'])
            ->getMock();

        $builder->expects($this->once())
            ->method('addCompilerPass');

        (new Api())->build($builder);
    }

    public function test_get_container_extension(): void
    {
        $this->assertInstanceOf(
            ApiExtension::class,
            (new Api())->getContainerExtension()
        );
    }
}
