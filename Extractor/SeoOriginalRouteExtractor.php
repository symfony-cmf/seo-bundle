<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exception\ModelNotSupportedException;
use Symfony\Cmf\Bundle\SeoBundle\Exception\SeoExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This strategy extracts the original route from documents
 * implementing the SeoOriginalRouteInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoOriginalRouteExtractor implements SeoExtractorInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritDoc}
     */
    public function supports($document)
    {
        return $document instanceof SeoOriginalRouteInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoOriginalRouteInterface $document
     */
    public function updateMetadata($document, SeoMetadataInterface $seoMetadata)
    {
        if (!$document instanceof SeoOriginalRouteInterface) {
            throw new ModelNotSupportedException($document);
        }

        $route = $document->getSeoOriginalRoute();

        try {
            $seoMetadata->setOriginalUrl($this->urlGenerator->generate($route));
        } catch (RouteNotFoundException $e) {
            throw new SeoExtractorStrategyException('Unable to create a url.', 0, $e);
        }
    }

    /**
     * Setter for the URL generator.
     *
     * @param UrlGeneratorInterface $router
     */
    public function setRouter(UrlGeneratorInterface $router)
    {
        $this->urlGenerator = $router;
    }
}
