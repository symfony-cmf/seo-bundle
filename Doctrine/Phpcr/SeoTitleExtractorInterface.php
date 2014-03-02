<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

/**
 * This interface is one of the ExtractorInterfaces to
 * get a documents property for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its
 * title for the SeoMetadata on its own, you should implement
 * this interface. It forces to implement the
 * extractTitle() method.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoTitleExtractorInterface
{
    /**
     * The return of this method is used to update the SeoMetada's title.
     *
     * It is used in an extractor strategy. At the moment the SeoBundle
     * supports the SeoTitleExtractorStrategy. This strategy checks for the
     * SeoTitleExtractorInterface and calls this method to get a
     * representation of the documents title.
     *
     * @return string
     */
    public function extractTitle();
}
