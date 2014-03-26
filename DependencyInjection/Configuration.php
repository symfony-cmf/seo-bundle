<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see.
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
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
                ->scalarNode('translation_domain')->defaultValue(null)->end()
                ->scalarNode('title')->end()
                ->scalarNode('description')->end()
                ->scalarNode('original_route_pattern')->defaultValue('canonical')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

