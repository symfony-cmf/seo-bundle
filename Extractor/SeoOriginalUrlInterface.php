<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

/**
 * This interface is one of the ExtractorInterfaces to
 * get document properties for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its original URL for
 * the SeoMetadata on its own, you should implement this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
interface SeoOriginalUrlInterface
{
    /**
     * The method returns the absolute URL as a string to redirect to or set to
     * the canonical link.
     *
     * @return string An absolute URL.
     */
    public function getSeoOriginalUrl();
}
