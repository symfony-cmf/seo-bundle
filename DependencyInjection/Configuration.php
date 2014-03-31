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

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentation;
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
            ->children()
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->getPhpcrNode())
                        ->append($this->getOrmNode())
                    ->end()
                ->end()
                ->scalarNode('translation_domain')->defaultValue(null)->end()
                ->scalarNode('title')->end()
                ->scalarNode('description')->end()
                ->scalarNode('original_route_pattern')->defaultValue(SeoPresentation::ORIGINAL_URL_CANONICAL)->end()
                ->scalarNode('content_key')->end()
            ->end()
        ;

        return $treeBuilder;
    }

    protected function getPhpcrNode()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('phpcr');

        $root
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->beforeNormalization()
                ->always()
                ->then(function ($v) {
                    if (!isset($v['use_metadata_listener'])) {
                        $v['use_metadata_listener'] = $v['enabled'];
                    }

                    return $v;
                })
            ->end()
            ->children()
                ->scalarNode('document_class')
                    ->defaultValue('Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent')
                ->end()
                ->scalarNode('admin_class')
                    ->defaultValue('Symfony\Cmf\Bundle\SeoBundle\Admin\SeoContentAdminExtension')
                ->end()
                ->booleanNode('use_metadata_listener')->defaultValue(null)->end()
                ->enumNode('use_sonata_admin')
                    ->values(array(true, false, 'auto'))
                    ->defaultValue('auto')
                ->end()
            ->end()
        ->end();

        return $root;
    }

    protected function getOrmNode()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('orm');

        $root
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->beforeNormalization()
                ->always()
                ->then(function ($v) {
                    if (!isset($v['use_metadata_listener'])) {
                        $v['use_metadata_listener'] = $v['enabled'];
                    }

                    return $v;
                })
            ->end()
            ->children()
                ->booleanNode('use_metadata_listener')->defaultValue(null)->end()
            ->end()
        ->end();

        return $root;
    }
}
