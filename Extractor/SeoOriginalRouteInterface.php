<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;

/**
 * This interface is one of the ExtractorInterfaces to
 * get a documents property for updating the SeoMetadata.
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
     * The method should return something that can be used to
     * generate a absolute url.
     *
     * This means it should return a symfony cmf Route object,
     * a symfony route key or a uuid of a route document stored
     * with the phpcr.
     *
     * @return Route|string
     */
    public function getSeoOriginalRoute();
}
