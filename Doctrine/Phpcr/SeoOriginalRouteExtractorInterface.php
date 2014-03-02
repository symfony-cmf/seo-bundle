<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

/**
 * This interface is one of the ExtractorInterfaces to
 * get a documents property for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its
 * original for the SeoMetadata on its own, you should implement
 * this interface. It forces to implement the
 * extractOriginalRoute() method.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoOriginalRouteExtractorInterface
{
    /**
     * The return of this method is used to update the SeoMetada's original route.
     *
     * It is used in an extractor strategy. At the moment the SeoBundle
     * supports the SeoOriginalRouteExtractorStrategy. This strategy checks for the
     * SeoOriginalRouteExtractorInterface and calls this method to get a
     * representation of the documents original route.
     *
     * This route will be used to set the href property of the canonical
     * link or process a redirect. This decision (if canonical or redirect)
     * is set in the config of the cmf_seo.content.strategy parameter.
     *
     * @return string
     */
    public function extractOriginalRoute();
}
