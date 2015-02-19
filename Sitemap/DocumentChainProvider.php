<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

/**
 * Accepts providers and merges the result of all providers into
 * a combined list of UrlInformation
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DocumentChainProvider extends SitemapItemChain implements DocumentsOnSitemapProviderInterface
{
     /**
     * {@inheritDoc}
     */
    public function getDocumentsForSitemap($sitemap)
    {
        $documents = array();

        foreach ($this->getSortedItemsBySitemap($sitemap) as $provider) {
            $documents = array_merge($documents, $provider->getDocumentsForSitemap($sitemap));
        }

        return $documents;
    }
}
