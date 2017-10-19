<?php

namespace Yay\Bundle\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\ApiBundle\DependencyInjection\ApiDocPass;
use Yay\Bundle\ApiBundle\DependencyInjection\ApiExtension;

class ApiBundle extends Bundle
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
