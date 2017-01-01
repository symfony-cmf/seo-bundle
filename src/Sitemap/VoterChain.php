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
 * Unanimous vote on the content. Any voter can veto exposing a content.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class VoterChain extends AbstractChain
{
    /**
     * Decide whether the voters want to expose the content.
     *
     * @param object $content The content to expose
     * @param string $sitemap Name of the sitemap
     *
     * @return bool whether to expose this content
     */
    public function exposeOnSitemap($content, $sitemap)
    {
        /** @var $voter VoterInterface */
        foreach ($this->getSortedEntriesForSitemap($sitemap) as $voter) {
            if (!$voter->exposeOnSitemap($content, $sitemap)) {
                return false;
            }
        }

        return true;
    }
}
