<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

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
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('phpcr')
                            ->addDefaultsIfNotSet()
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('document_class')
                                    ->defaultValue('Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent')
                                ->end()
                                ->scalarNode('admin_class')
                                    ->defaultValue('Symfony\Cmf\Bundle\SeoBundle\Admin\SeoContentAdminExtension')
                                ->end()
                                ->scalarNode('content_basepath')->defaultValue('/cms/content')->end()
                                ->enumNode('use_sonata_admin')
                                    ->values(array(true, false, 'auto'))
                                    ->defaultValue('auto')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('title')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('strategy')
                            ->values(array('prepend', 'append', 'replace'))
                            ->defaultValue('prepend')
                        ->end()
                        ->scalarNode('separator')->defaultValue('')->end()
                        ->variableNode('default')
                            ->validate()
                                ->ifTrue(function ($v) {
                                    return !is_string($v) || !is_array($v);
                                })
                                ->thenInvalid('Default can either be an array or a string, "%s" given')
                            ->end()
                        ->end()
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
