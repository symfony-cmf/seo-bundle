<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Component\Routing\Route;

/**
 * This interface is one of the ExtractorInterfaces to
 * get document properties for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its
 * original route for the SeoMetadata on its own, you should implement
 * this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoOriginalRouteInterface
{
    /**
     * Returns something that can be used to generate an absolute URL.
     *
     * This may be a Symfony route name or - when using the Symfony CMF
     * DynamicRouter - a Route object.
     *
     * @return Route|string
     */
    public function getSeoOriginalRoute();
}
