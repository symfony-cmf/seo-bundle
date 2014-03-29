<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exception\ModelNotSupportedException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This extractor sets the absolute URL on the SeoMetadata.
 *
 * If you have a Symfony Route, use the SeoOriginalRouteExtractor.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class SeoOriginalUrlExtractor implements SeoExtractorInterface
{

    /**
     * {@inheritDoc}
     */
    public function supports($document)
    {
        return $document instanceof SeoOriginalUrlInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function updateMetadata($document, SeoMetadataInterface $seoMetadata)
    {
        if (null === $seoMetadata->getOriginalUrl() || '' === $seoMetadata->getOriginalUrl()) {
            $seoMetadata->setOriginalUrl($document->getSeoOriginalUrl());
        }
    }
}
