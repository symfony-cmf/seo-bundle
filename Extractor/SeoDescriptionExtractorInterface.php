<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

/**
 * This interface is one of the ExtractorInterfaces to
 * get a documents property for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its
 * description for the SeoMetadata on its own, you should implement
 * this interface. It forces to implement the
 * extractDescription() method.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoDescriptionExtractorInterface
{
    /**
     * Provide a description of this page to be used in SEO context.
     *
     * @return string
     */
    public function extractDescription();
}
