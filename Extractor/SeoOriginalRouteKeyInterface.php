<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

/**
 * This interface is one of the ExtractorInterfaces to
 * get a documents property for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its
 * original route for the SeoMetadata on its own, you should implement
 * this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
interface SeoOriginalRouteKeyInterface
{
    /**
     * This method returns the symfony route key as a string.
     *
     * @return string
     */
    public function getSeoOriginalRouteKey();
}
 