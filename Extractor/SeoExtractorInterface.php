<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\ModelNotSupportedException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * An extractor strategy updates the SeoMetadata from a document.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoExtractorInterface
{
    /**
     * Check whether the strategy supports this document.
     *
     * The decision could be based on the object implementing
     * an interface or being instance of a specific class,
     * or introspection to see if a certain method exists.
     *
     * @param object $document
     *
     * @return boolean whether this strategy supports $document
     */
    public function supports($document);

    /**
     * Update the metadata object with information from this document.
     *
     * It is up to the strategy to check if certain fields
     * are already set by previous strategies and decide on a merge strategy.
     *
     * This method is only called if supports returned true.
     *
     * @param object               $document
     * @param SeoMetadataInterface $seoMetadata
     *
     * @throws ModelNotSupportedException If $document is not supported by this
     *                                    extractor. Call supports() first to
     *                                    avoid this exception.
     */
    public function updateMetadata($document, SeoMetadataInterface $seoMetadata);
}
