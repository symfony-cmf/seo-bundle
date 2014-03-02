<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the seo metadata from documents.
 *
 * This strategy is used for all documents that implements the
 * SeoAwareInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadataExtractorStrategy implements SeoExtractorStrategyInterface
{

    /**
     * {@inheritDoc}
     */
    public function supports(SeoAwareInterface $document)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoDescriptionExtractorInterface $document
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        //just copying the documents seo metadata
        $seoMetadata->setTitle($document->getSeoMetadata()->getTitle());
        $seoMetadata->setMetaDescription($document->getSeoMetadata()->getMetaDescription());
        $seoMetadata->setMetaKeywords($document->getSeoMetadata()->getMetaKeywords());
        $seoMetadata->setOriginalUrl($document->getSeoMetadata()->getOriginalUrl());
    }
}
