<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Component\Routing\Router;

/**
 * This strategy extracts the original route from documents
 * implementing the SeoOriginalRouteInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoOriginalRouteExtractor implements SeoExtractorInterface
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
        return $document instanceof SeoOriginalRouteInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoOriginalRouteInterface $document
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        if (!$document instanceof SeoOriginalRouteInterface) {
            throw new SeoExtractorStrategyException(
                sprintf(
                    'The given document %s is not supported by this strategy. Call supports() method first.',
                    get_class($document)
                )
            );
        }

        $route = $document->getSeoOriginalRoute();
        if (!$route instanceof Route) {
            throw new SeoExtractorStrategyException(
                sprintf('Expecting Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route but got %s.', get_class($route))
            );
        }

        $seoMetadata->setOriginalUrl($this->router->generate($route));
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
