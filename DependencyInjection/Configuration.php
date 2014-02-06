<?php

namespace Cmf\SeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cmf_seo');


        $rootNode
            ->children()
                ->arrayNode('title')
                    ->children()
                        ->scalarNode('strategy')->defaultValue('prepend')->end()
                        ->scalarNode('default')->end()
                        ->scalarNode('bond_by')->defaultValue(' - ')->end()
                    ->end()
                ->end()
                ->scalarNode('description')->end()
                ->scalarNode('keys')->end()
                ->arrayNode('content')
                    ->children()
                        ->scalarNode('strategy')->defaultValue('redirect')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
