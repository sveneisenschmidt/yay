<?php

namespace Yay\Bundle\IntegrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\IntegrationBundle\DependencyInjection\IntegrationExtension;

class IntegrationBundle extends Bundle
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
