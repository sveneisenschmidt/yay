<?php

namespace Yay\Bundle\IntegrationBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Yay\Bundle\IntegrationBundle\DependencyInjection\IntegrationExtension;
use Yay\Bundle\IntegrationBundle\IntegrationBundle;

class IntegrationBundleTest extends WebTestCase
{
    /**
     * @test
     */
    public function get_container_extension()
    {
        $this->assertInstanceOf(
            IntegrationExtension::class,
            (new IntegrationBundle())->getContainerExtension()
        );
    }
}
