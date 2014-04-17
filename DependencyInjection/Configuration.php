<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

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
            ->children()
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('phpcr')
                            ->canBeEnabled()
                        ->end() // phpcr
                        ->arrayNode('orm')
                            ->canBeEnabled()
                        ->end() // orm
                    ->end()
                ->end() // persistence
                ->scalarNode('translation_domain')->defaultValue('messages')->end()
                ->scalarNode('title')->end()
                ->scalarNode('description')->end()
                ->scalarNode('original_route_pattern')->defaultValue(SeoPresentation::ORIGINAL_URL_CANONICAL)->end()
                ->scalarNode('content_key')->end()
                ->enumNode('metadata_listener')
                    ->values(array(true, false, 'auto'))
                    ->defaultValue('auto')
                ->end()
                ->enumNode('sonata_admin_extension')
                    ->values(array(true, false, 'auto'))
                    ->defaultValue('auto')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
