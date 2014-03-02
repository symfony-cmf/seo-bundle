<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

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
     * Provide the original url of this page to be used in SEO context.
     *
     * @return string as the route name (cmf Route object)
     */
    public function extractOriginalRoute();
}
