<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\ModelNotSupported;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the title from documents
 * implementing the SeoTitleInterface.
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
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        if (!$document instanceof SeoTitleInterface) {
            throw new ModelNotSupported($document);
        }

        $seoMetadata->setTitle($document->getSeoTitle());
    }
}
