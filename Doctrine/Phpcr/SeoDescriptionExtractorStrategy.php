<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This extractor strategy is responsible for extracting
 * ths documents description.
 *
 * That document needs to implement the SeoDescriptionExtractorInterface
 * and provide a extractDescription() method.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoOriginalDescriptionStrategy implements SeoExtractorStrategyInterface
{

    /**
     * {@inheritDoc}
     */
    public function supports(SeoAwareInterface $document)
    {
        return $document instanceof SeoDescriptionExtractorInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoDescriptionExtractorInterface $document
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setOriginalUrl($document->extractDescription());
    }
}
