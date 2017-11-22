<?php

namespace App\Integration;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Integration\DependencyInjection\IntegrationExtension;

class Integration extends Bundle
{
    public function build(ContainerBuilder $container)
    {
    }

    public function getContainerExtension(): IntegrationExtension
    {
        return new IntegrationExtension();
    }
}
