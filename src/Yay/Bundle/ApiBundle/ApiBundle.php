<?php

namespace Yay\Bundle\ApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
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
