<?php

namespace Yay\Bundle\CompatBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Yay\Bundle\CompatBundle\DependencyInjection\CompatExtension;
use Yay\Bundle\CompatBundle\DependencyInjection\JMSSerializerBundlePass;
use Yay\Bundle\CompatBundle\DependencyInjection\SncRedisBundlePass;


class CompatBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new JMSSerializerBundlePass());
        $container->addCompilerPass(new SncRedisBundlePass());
    }

    /**
     * @return CompatExtension
     */
    public function getContainerExtension()
    {
        return new CompatExtension();
    }
}
