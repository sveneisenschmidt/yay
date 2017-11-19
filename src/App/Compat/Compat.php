<?php

namespace App\Compat;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Compat\DependencyInjection\CompatExtension;
use App\Compat\DependencyInjection\JMSSerializerBundlePass;
use App\Compat\DependencyInjection\SncRedisBundlePass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class Compat extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new JMSSerializerBundlePass(), PassConfig::TYPE_AFTER_REMOVING, -255);
        $container->addCompilerPass(new SncRedisBundlePass(), PassConfig::TYPE_AFTER_REMOVING, -255);
    }

    /**
     * @return CompatExtension
     */
    public function getContainerExtension()
    {
        return new CompatExtension();
    }
}