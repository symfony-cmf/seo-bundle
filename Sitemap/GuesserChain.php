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

use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Component\Routing\RouterInterface;

/**
 * Guess values with prioritized guessers.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class GuesserChain extends AbstractChain
{
    /**
     * {@inheritDocs}
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap)
    {
        /** @var $guesser GuesserInterface */
        foreach ($this->getSortedEntriesForSitemap($sitemap) as $guesser) {
            $guesser->guessValues($urlInformation, $object, $sitemap);
        }
    }
}
