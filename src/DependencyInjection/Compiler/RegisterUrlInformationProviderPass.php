<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register the tagged services for the url information provider:.
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
     * {@inheritdoc}
     *
     * @throws LogicException if a tagged service is not public
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('cmf_seo.sitemap.configurations')) {
            return;
        }
        $sitemaps = array_keys($container->getParameter('cmf_seo.sitemap.configurations'));

        $this->processTagsForService(
            $container,
            'cmf_seo.sitemap.loader_chain',
            'cmf_seo.sitemap.loader',
            $sitemaps
        );

        $this->processTagsForService(
            $container,
            'cmf_seo.sitemap.voter_chain',
            'cmf_seo.sitemap.voter',
            $sitemaps
        );

        $this->processTagsForService(
            $container,
            'cmf_seo.sitemap.guesser_chain',
            'cmf_seo.sitemap.guesser',
            $sitemaps
        );
    }

    /**
     * Add tagged services with priority and sitemap parameter.
     *
     * @param ContainerBuilder $container
     * @param string           $service   ID of service to add tagged services to
     * @param string           $tag       Tag name
     * @param string[]         $sitemaps  list of valid sitemap names
     */
    private function processTagsForService(ContainerBuilder $container, $service, $tag, array $sitemaps)
    {
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
                $sitemaps = [null];
            }

            foreach ($sitemaps as $sitemap) {
                if ($sitemap && !in_array($sitemap, $sitemaps)) {
                    throw new InvalidConfigurationException(sprintf(
                        'Service %s tagged with %s specifies sitemap %s but that sitemap is not configured',
                        $id,
                        $tag,
                        $sitemap
                    ));
                }
                $serviceDefinition->addMethodCall(
                    'addItem',
                    [new Reference($id), $priority, $sitemap]
                );
            }
        }
    }
}
