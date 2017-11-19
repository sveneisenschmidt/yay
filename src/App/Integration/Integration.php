<?php

namespace App\Integration;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Integration\DependencyInjection\IntegrationExtension;

class Integration extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
    }

    /**
     * @return IntegrationExtension
     */
    public function getContainerExtension()
    {
        return new IntegrationExtension();
    }
}
