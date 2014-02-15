<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

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

        $treeBuilder->root('cmf_seo')
            ->children()
                ->arrayNode('title')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('strategy')
                            ->values(array('prepend', 'append', 'replace'))
                            ->defaultValue('prepend')
                        ->end()
                        ->scalarNode('separator')->defaultValue('')->end()
                    ->end()
                ->end()
                ->arrayNode('content')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('strategy')
                            ->values(array('redirect', 'canonical'))
                            ->defaultValue('canonical')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
