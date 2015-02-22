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
class UrlInformationProvider
{
    /**
     * @var DocumentsOnSitemapProviderInterface
     */
    private $loader;

    /**
     * @var UrlInformationGuesserInterface
     */
    private $guesser;

    /**
     * @var ContentForSitemapVoterInterface
     */
    private $voter;

    /**
     * @param DocumentsOnSitemapProviderInterface $loader
     * @param UrlInformationGuesserInterface $guesser
     * @param ContentForSitemapVoterInterface $voter
     */
    public function __construct(
        DocumentsOnSitemapProviderInterface $loader,
        UrlInformationGuesserInterface $guesser,
        ContentForSitemapVoterInterface $voter
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
