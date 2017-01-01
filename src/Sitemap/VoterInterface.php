<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param object $content the content object
     * @param string $sitemap name of the sitemap
     *
     * @return bool true if the content should be visible on the sitemap, false otherwise
     */
    public function exposeOnSitemap($content, $sitemap);
}
