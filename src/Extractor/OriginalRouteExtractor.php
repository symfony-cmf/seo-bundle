<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exception\ExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This strategy extracts the original route from content
 * implementing the OriginalRouteReadInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class OriginalRouteExtractor implements ExtractorInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return $content instanceof OriginalRouteReadInterface;
    }

    /**
     * {@inheritdoc}
     *
     * @param OriginalRouteReadInterface $content
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        $route = $content->getSeoOriginalRoute();

        try {
            $seoMetadata->setOriginalUrl($this->urlGenerator->generate($route));
        } catch (RouteNotFoundException $e) {
            throw new ExtractorStrategyException('Unable to create a url.', 0, $e);
        }
    }
}
