<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Router;

/**
 * Contrary to the SeoOriginalRouteExtractor this one will set a
 * a symfony route key as a string to the SeoMetadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class SeoOriginalRouteKeyExtractor implements SeoExtractorInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * {@inheritDoc}
     */
    public function supports(SeoAwareInterface $document)
    {
        return $document instanceof SeoOriginalRouteKeyInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        if (!$document instanceof SeoOriginalRouteKeyInterface) {
            throw new SeoExtractorStrategyException(
                sprintf(
                    'The given document %s is not supported by this strategy. Call supports() method first.',
                    get_class($document)
                )
            );
        }

        $routeKey = $document->getSeoOriginalRouteKey();
        if (!is_string($routeKey)) {
            throw new SeoExtractorStrategyException(sprintf('Expecting string but got %s.', gettype($routeKey)));
        }

        try {
            $absoluteUrl = $this->router->generate($document->getSeoOriginalRouteKey());
        } catch(RouteNotFoundException $e) {
            throw new SeoExtractorStrategyException(
                sprintf('Given symfony route key seems to be wrong.', $document->getSeoOriginalRouteKey()), 0, $e
            );
        }
        $seoMetadata->setOriginalUrl($absoluteUrl);
    }

    /**
     * Setter for the symfony router.
     *
     * @param Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }
}
 