<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This extractor strategy is responsible for extracting
 * ths documents title.
 *
 * That document needs to implement the SeoTitleExtractorInterface
 * and provide a extractTitle() method.
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
        return $document instanceof SeoTitleExtractorInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoTitleExtractorInterface $document
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setOriginalUrl($document->extractTitle());
    }
}
