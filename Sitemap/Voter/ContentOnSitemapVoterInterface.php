<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap\Voter;

/**
 * Voters for content exposed on a sitemap should implement this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
interface ContentOnSitemapVoterInterface
{
    /**
     * A voter should decide whether a content object should be exposed on a sitemap.
     *
     * @param object $content
     * @param string $sitemap
     *
     * @return bool
     */
    public function exposeOnSitemap($content, $sitemap = 'default');
}
