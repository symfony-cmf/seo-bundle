<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

/**
 * Sitemap voters decide whether a content should appear on a specific sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
interface VoterInterface
{
    /**
     * Decide whether this content is visible on the specified sitemap.
     *
     * @param object $content The content object.
     * @param string $sitemap Name of the sitemap.
     *
     * @return bool True if the content should be visible on the sitemap, false otherwise.
     */
    public function exposeOnSitemap($content, $sitemap);
}
