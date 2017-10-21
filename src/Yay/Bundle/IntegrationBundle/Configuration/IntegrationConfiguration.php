<?php

namespace Yay\Bundle\IntegrationBundle\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class IntegrationConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('integration');

        $rootNode
            ->children()
                ->arrayNode('actions')
                    ->normalizeKeys(false)
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('description')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('achievements')
                    ->normalizeKeys(false)
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('label')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('description')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->integerNode('points')
                                ->isRequired()
                            ->end()
                            ->arrayNode('actions')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('validators')
                    ->normalizeKeys(false)
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')
                                ->validate()
                                ->ifNotInArray(['expression', 'class'])
                                    ->thenInvalid('Invalid type %s')
                                ->end()
                            ->end()
                            ->scalarNode('class')
                                ->defaultNull()
                            ->end()
                            ->arrayNode('arguments')
                                ->defaultValue([])
                                ->prototype('scalar')->end()
                            ->end()
                            ->variableNode('calls')
                                ->defaultValue([])
                                ->validate()
                                ->ifString()
                                    ->thenInvalid('Configuration value must be array.')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
