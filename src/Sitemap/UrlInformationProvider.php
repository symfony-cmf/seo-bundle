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

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Provide a list of UrlInformation objects for a sitemap.
 *
 * The provider loads objects from the registered content loaders, filters them
 * for publication and then uses the guesser to extract the UrlInformation.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class UrlInformationProvider
{
    /**
     * @var LoaderChain
     */
    private $loader;

    /**
     * @var GuesserChain
     */
    private $guesser;

    /**
     * @var VoterChain
     */
    private $voter;

    public function __construct(
        LoaderChain $loader,
        VoterChain $voter,
        GuesserChain $guesser
    ) {
        $this->loader = $loader;
        $this->voter = $voter;
        $this->guesser = $guesser;
    }

    /**
     * Get the UrlInformation for the specified sitemap.
     *
     * @param string $sitemap Name of the sitemap to create, "default" if not specified
     *
     * @return UrlInformation[]
     */
    public function getUrlInformation($sitemap = 'default')
    {
        $urlInformationList = array();

        $contentObjects = $this->loader->load($sitemap);
        foreach ($contentObjects as $content) {
            if (!$this->voter->exposeOnSitemap($content, $sitemap)) {
                continue;
            }
            $urlInformation = new UrlInformation();
            $this->guesser->guessValues($urlInformation, $content, $sitemap);
            $urlInformationList [] = $urlInformation;
        }

        return $urlInformationList;
    }
}
