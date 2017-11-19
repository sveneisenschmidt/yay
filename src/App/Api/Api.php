<?php

namespace App\Api;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Api\DependencyInjection\ApiDocPass;
use App\Api\DependencyInjection\ApiExtension;

class Api extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ApiDocPass());
    }

    /**
     * @return ApiExtension
     */
    public function getContainerExtension()
    {
        return new ApiExtension();
    }
}
