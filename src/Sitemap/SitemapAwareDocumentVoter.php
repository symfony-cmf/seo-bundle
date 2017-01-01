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

use Symfony\Cmf\Bundle\SeoBundle\SitemapAwareInterface;

/**
 * Implementing the SitemapAwareInterface will give the chance the let the document decide
 * whether it wants to be displayed on sitemap or not.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class SitemapAwareDocumentVoter implements VoterInterface
{
    /**
     * {@inheritdoc}
     */
    public function exposeOnSitemap($content, $sitemap)
    {
        if (!$content instanceof SitemapAwareInterface) {
            return true;
        }

        return $content->isVisibleInSitemap($sitemap);
    }
}
