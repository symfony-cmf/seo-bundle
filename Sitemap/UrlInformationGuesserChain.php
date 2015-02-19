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

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * A chain to register all url information guesser by its priority and preferred sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class UrlInformationGuesserChain extends SitemapItemChain implements UrlInformationGuesserInterface
{
    /**
     * {@inheritDocs}
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap = 'default')
    {
        foreach ($this->getSortedItemsBySitemap($sitemap) as $guesser) {
            $guesser->guessValues($urlInformation, $object, $sitemap);
        }
    }
}
