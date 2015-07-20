<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register the tagged services for the url information provider:
 *
 * - cmf_seo.sitemap.loader
 * - cmf_seo.sitemap.voter
 * - cmf_seo.sitemap.guesser
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterUrlInformationProviderPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws LogicException If a tagged service is not public.
     */
    public function process(ContainerBuilder $container)
    {
        $this->processTagsForService(
            'cmf_seo.sitemap.loader_chain',
            'cmf_seo.sitemap.loader',
            $container
        );

        $this->processTagsForService(
            'cmf_seo.sitemap.voter_chain',
            'cmf_seo.sitemap.voter',
            $container
        );

        $this->processTagsForService(
            'cmf_seo.sitemap.guesser_chain',
            'cmf_seo.sitemap.guesser',
            $container
        );
    }

    /**
     * @param $service
     * @param $tag
     * @param ContainerBuilder $container
     */
    private function processTagsForService($service, $tag, ContainerBuilder $container)
    {
        // feature not activated means nothing to add
        if (!$container->hasDefinition($service)) {
            return;
        }

        $serviceDefinition = $container->getDefinition($service);
        $taggedServices = $container->findTaggedServiceIds($tag);

        foreach ($taggedServices as $id => $attributes) {
            $priority = null;
            foreach ($attributes as $attribute) {
                if (isset($attribute['priority'])) {
                    $priority = $attribute['priority'];
                    break;
                }
            }

            $sitemap = null;
            foreach ($attributes as $attribute) {
                if (isset($attribute['sitemap'])) {
                    $sitemap = $attribute['sitemap'];
                    break;
                }
            }
            if ($sitemap) {
                $sitemaps = explode(',', $sitemap);
            } else {
                $sitemaps = array(null);
            }

            foreach ($sitemaps as $sitemap) {
                $serviceDefinition->addMethodCall(
                    'addItem',
                    array(new Reference($id), $priority, $sitemap)
                );
            }
        }
    }
}
