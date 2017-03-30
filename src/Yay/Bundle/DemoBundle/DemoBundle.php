<?php

namespace Yay\Bundle\DemoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Yay\Bundle\DemoBundle\DependencyInjection\DemoExtension;

class DemoBundle extends Bundle
{
    /**
     * @return DemoExtension
     */
    public function getContainerExtension()
    {
        return new DemoExtension();
    }
}
