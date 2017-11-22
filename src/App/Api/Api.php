<?php

namespace App\Api;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Api\DependencyInjection\ApiDocPass;
use App\Api\DependencyInjection\ApiExtension;

class Api extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ApiDocPass());
    }

    public function getContainerExtension(): ApiExtension
    {
        return new ApiExtension();
    }
}
