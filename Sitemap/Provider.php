<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Create a list of url information.
 *
 * Based on the registered content loaders the guesser will extract values for the url information.
 * The list of served content objects can be reduced by voters.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class Provider
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var GuesserInterface
     */
    private $guesser;

    /**
     * @var VoterInterface
     */
    private $voter;

    /**
     * @param LoaderInterface $loader
     * @param GuesserInterface $guesser
     * @param VoterInterface $voter
     */
    public function __construct(
        LoaderInterface $loader,
        GuesserInterface $guesser,
        VoterInterface $voter
    ) {
        $this->loader = $loader;
        $this->guesser = $guesser;
        $this->voter = $voter;
    }

    /**
     * @param string $sitemap
     *
     * @return UrlInformation[]
     */
    public function create($sitemap = 'default')
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
