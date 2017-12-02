<?php

namespace App\Integration\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Integration\DependencyInjection\IntegrationExtension;
use App\Integration\Integration;

class IntegrationTest extends WebTestCase
{
    public function test_build(): void
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCompilerPass'])
            ->getMock();

        $builder->expects($this->exactly(1))
            ->method('addCompilerPass');

        (new Integration())->build($builder);
    }

    public function test_get_container_extension(): void
    {
        $this->assertInstanceOf(
            IntegrationExtension::class,
            (new Integration())->getContainerExtension()
        );
    }
}
