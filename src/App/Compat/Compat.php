<?php

namespace App\Compat;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Compat\DependencyInjection\CompatExtension;
use App\Compat\DependencyInjection\JMSSerializerBundlePass;
use App\Compat\DependencyInjection\SncRedisBundlePass;
use App\Compat\DependencyInjection\NelmioApiDocBundlePass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class Compat extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new JMSSerializerBundlePass(), PassConfig::TYPE_AFTER_REMOVING, -255);
        $container->addCompilerPass(new SncRedisBundlePass(), PassConfig::TYPE_AFTER_REMOVING, -255);
        $container->addCompilerPass(new NelmioApiDocBundlePass(), PassConfig::TYPE_AFTER_REMOVING, -255);
    }

    public function getContainerExtension(): CompatExtension
    {
        return new CompatExtension();
    }
}
