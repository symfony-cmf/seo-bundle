<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

/**
 * Models implementing this interface can indicate whether they should be listed in the sitemap
 * with a given sitemap name or not.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SitemapAwareInterface
{
    /**
     * Decision whether a document should be visible
     * in sitemap or not.
     *
     * @param $sitemap
     *
     * @return bool
     */
    public function isVisibleInSitemap($sitemap);
}
