<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exception\ModelNotSupportedException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the description from documents implementing the
 * SeoDescriptionInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoDescriptionExtractor implements SeoExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($document)
    {
        return $document instanceof SeoDescriptionInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoDescriptionInterface $document
     */
    public function updateMetadata($document, SeoMetadataInterface $seoMetadata)
    {
        if (!$document instanceof SeoDescriptionInterface) {
            throw new ModelNotSupportedException($document);
        }

        if (null === $seoMetadata->getMetaDescription() || '' === $seoMetadata->getMetaDescription()) {
           $seoMetadata->setMetaDescription($document->getSeoDescription());
        }
    }
}
