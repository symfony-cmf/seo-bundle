<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exception\ModelNotSupportedException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the title from documents implementing the
 * SeoTitleInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoTitleExtractor implements SeoExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($document)
    {
        return $document instanceof SeoTitleInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoTitleInterface $document
     */
    public function updateMetadata($document, SeoMetadataInterface $seoMetadata)
    {
        if (!$document instanceof SeoTitleInterface) {
            throw new ModelNotSupportedException($document);
        }

        if (null === $seoMetadata->getTitle() || '' === $seoMetadata->getTitle()) {
            $seoMetadata->setTitle($document->getSeoTitle());
        }
    }
}
