<?php

namespace App\Api\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use App\Api\Formatter\MarkdownFormatter;

class ApiDocPass implements CompilerPassInterface
{
    /**
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('nelmio_api_doc.formatter.markdown_formatter');
        $definition->setClass(MarkdownFormatter::class);
    }
}
