<?php

namespace App\Integration\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Integration\DependencyInjection\IntegrationExtension;
use App\Integration\Integration;

class IntegrationTest extends WebTestCase
{
    public function test_get_container_extension(): void
    {
        $this->assertInstanceOf(
            IntegrationExtension::class,
            (new Integration())->getContainerExtension()
        );
    }
}
