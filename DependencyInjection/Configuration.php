<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validates and merges the configuration.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
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
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->ifTrue(function ($config) {
                    return isset($config['content_key']) && !isset($config['content_listener']['content_key']);
                })
                ->then(function ($config) {
                    $config['content_listener']['content_key'] = $config['content_key'];
                    unset($config['content_key']);

                    return $config;
                })
            ->end()
            // validation needs to be on top, when no values are set a validation inside the content_listener array node will not be triggered
            ->validate()
                ->ifTrue(function ($v) { return $v['content_listener']['enabled'] && empty($v['content_listener']['content_key']); })
                ->thenInvalid('Configure the content_listener.content_key or disable the content_listener when not using the CmfRoutingBundle DynamicRouter.')
            ->end()
            ->children()
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('phpcr')
                            ->addDefaultsIfNotSet()
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('manager_name')->defaultNull()->end()
                            ->end()
                        ->end() // phpcr
                        ->arrayNode('orm')
                            ->addDefaultsIfNotSet()
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('manager_name')->defaultNull()->end()
                            ->end()
                        ->end() // orm
                    ->end()
                ->end() // persistence
                ->scalarNode('translation_domain')->defaultValue('messages')->end()
                ->scalarNode('title')->end()
                ->scalarNode('description')->end()
                ->scalarNode('original_route_pattern')->defaultValue(SeoPresentation::ORIGINAL_URL_CANONICAL)->end()
                ->arrayNode('sonata_admin_extension')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifTrue( function ($v) { return is_scalar($v); })
                        ->then(function ($v) {
                            return array('enabled' => $v);
                        })
                    ->end()
                    ->children()
                        ->enumNode('enabled')
                            ->values(array(true, false, 'auto'))
                            ->defaultValue('auto')
                        ->end()
                        ->scalarNode('form_group')->defaultValue('form.group_seo')->end()
                    ->end()
                ->end()
                ->arrayNode('alternate_locale')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('provider_id')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('error')
                    ->children()
                        ->scalarNode('enable_parent_provider')->defaultValue(false)->end()
                        ->scalarNode('enable_sibling_provider')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('sitemap')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('configurations')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) { return array('name' => $v); })
                                ->end()
                                ->treatNullLike(array())
                                ->children()
                                    ->scalarNode('default_change_frequency')->defaultValue('always')->end()
                                    ->arrayNode('templates')
                                        ->requiresAtLeastOneElement()
                                        ->prototype('scalar')->end()
                                    ->end()
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
