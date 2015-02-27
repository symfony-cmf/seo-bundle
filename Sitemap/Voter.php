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
     * First comes first - The first negative voter decides.
     */
    public function exposeOnSitemap($content, $sitemap = 'default')
    {
        foreach ($this->getSortedItemsBySitemap($sitemap) as $voter) {
            if (!$voter->exposeOnSitemap($content, $sitemap)) {
                return false;
            }
        }

        return true;
    }
}
