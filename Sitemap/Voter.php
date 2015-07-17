<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

/**
 * A chain as a decorator for all tagged voter services.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class Voter extends AbstractChain implements VoterInterface
{
    /**
     * {@inheritDocs}
     *
     * In terms of symfony security, this would be called "unanimous": every voter can veto a content.
     */
    public function exposeOnSitemap($content, $sitemap = 'default')
    {
        foreach ($this->getSortedItemsForSitemap($sitemap) as $voter) {
            if (!$voter->exposeOnSitemap($content, $sitemap)) {
                return false;
            }
        }

        return true;
    }
}
