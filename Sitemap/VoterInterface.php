<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
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
     * @param object $content The content object.
     * @param string $sitemap Name of the sitemap.
     *
     * @return bool True if the content should be visible on the sitemap, false otherwise.
     */
    public function exposeOnSitemap($content, $sitemap);
}
