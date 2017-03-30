<?php

namespace Yay\Bundle\ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

use Yay\Bundle\ApiBundle\Formatter\MarkdownFormatter;

class ApiDocPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('nelmio_api_doc.formatter.markdown_formatter');
        $definition->setClass(MarkdownFormatter::class);
    }
}
