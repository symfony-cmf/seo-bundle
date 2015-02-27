<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\Guesser\UrlInformationGuesserInterface;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\Voter\ContentOnSitemapVoterInterface;

/**
 * Create a list of url information.
 *
 * Based on the registered content loaders the guesser will extract values for the url information.
 * The list of served content objects can be reduced by voters.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class UrlInformationProvider
{
    /**
     * @var ContentOnSitemapProviderInterface
     */
    private $loader;

    /**
     * @var UrlInformationGuesserInterface
     */
    private $guesser;

    /**
     * @var ContentOnSitemapVoterInterface
     */
    private $voter;

    /**
     * @param ContentOnSitemapProviderInterface $loader
     * @param UrlInformationGuesserInterface $guesser
     * @param ContentOnSitemapVoterInterface $voter
     */
    public function __construct(
        ContentOnSitemapProviderInterface $loader,
        UrlInformationGuesserInterface $guesser,
        ContentOnSitemapVoterInterface $voter
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

        $documents = $this->loader->getDocumentsForSitemap($sitemap);
        foreach ($documents as $document) {
            $urlInformation = new UrlInformation();
            $this->guesser->guessValues($urlInformation, $document, $sitemap);
            $urlInformationList [] = $urlInformation;
        }

        return $urlInformationList;
    }
}
