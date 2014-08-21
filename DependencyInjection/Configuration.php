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
                ->scalarNode('content_key')->end()
                ->arrayNode('sonata_admin_extension')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifTrue( function ($v) { return is_scalar($v); })
                        ->then( function ($v) {
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
            ->end()
        ;

        return $treeBuilder;
    }
}
