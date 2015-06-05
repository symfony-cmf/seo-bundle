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
 * Loader chain, to decorate all loaders registered by a tag.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Loader extends AbstractChain implements LoaderInterface
{
     /**
     * {@inheritDoc}
     */
    public function load($sitemap)
    {
        $documents = array();

        foreach ($this->getSortedItemsForSitemap($sitemap) as $provider) {
            $documents = array_merge($documents, $provider->load($sitemap));
        }

        return $documents;
    }
}
