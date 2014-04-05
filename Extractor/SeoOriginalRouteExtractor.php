<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exception\SeoExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This strategy extracts the original route from documents
 * implementing the SeoOriginalRouteReadInterface.
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
    public function supports($object)
    {
        return $object instanceof SeoOriginalRouteReadInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoOriginalRouteReadInterface $object
     */
    public function updateMetadata($object, SeoMetadataInterface $seoMetadata)
    {
        $route = $object->getSeoOriginalRoute();

        try {
            $seoMetadata->setOriginalUrl($this->urlGenerator->generate($route));
        } catch (RouteNotFoundException $e) {
            throw new SeoExtractorStrategyException('Unable to create a url.', 0, $e);
        }
    }

    /**
     * Sets the URL generator.
     *
     * @param UrlGeneratorInterface $router
     */
    public function setRouter(UrlGeneratorInterface $router)
    {
        $this->urlGenerator = $router;
    }
}
