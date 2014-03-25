<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\DocumentNotSupport;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the description from documents
 * implementing the SeoDescriptionInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoDescriptionExtractor implements SeoExtractorInterface
{

    /**
     * {@inheritDoc}
     */
    public function supports(SeoAwareInterface $document)
    {
        return $document instanceof SeoDescriptionInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoDescriptionInterface $document
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        if (!$document instanceof SeoDescriptionInterface) {
            throw new DocumentNotSupport($document);
        }

        $seoMetadata->setMetaDescription($document->getSeoDescription());
    }
}
