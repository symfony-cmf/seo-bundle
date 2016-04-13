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
 * Load documents with all registered loaders.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class LoaderChain extends AbstractChain
{
    /**
     * {@inheritdoc}
     */
    public function load($sitemap)
    {
        $documents = array();

        /** @var $loader LoaderInterface */
        foreach ($this->getSortedEntriesForSitemap($sitemap) as $loader) {
            $documents = array_merge($documents, $loader->load($sitemap));
        }

        return $documents;
    }
}
