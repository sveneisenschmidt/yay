<?php

namespace App\Integration\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Integration\DependencyInjection\IntegrationExtension;
use App\Integration\Integration;

class IntegrationTest extends WebTestCase
{
    /**
     * @test
     */
    public function get_container_extension()
    {
        $this->assertInstanceOf(
            IntegrationExtension::class,
            (new Integration())->getContainerExtension()
        );
    }
}
