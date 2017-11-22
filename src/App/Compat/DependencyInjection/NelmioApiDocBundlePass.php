<?php

namespace App\Compat\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class NelmioApiDocBundlePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definitions = [
            'nelmio_api_doc.extractor.api_doc_extractor',
            'nelmio_api_doc.formatter.html_formatter',
        ];

        foreach ($definitions as $definition) {
            if ($container->hasDefinition($definition)) {
                $container->getDefinition($definition)->setPublic(true);
            }
        }
    }
}
