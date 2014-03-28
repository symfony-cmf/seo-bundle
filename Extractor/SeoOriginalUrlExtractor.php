<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\ModelNotSupported;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * Contrary to the SeoOriginalRouteExtractor this one will set a
 * absolute url as a string to the SeoMetadata.
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
        if (!$document instanceof SeoOriginalUrlInterface) {
            throw new ModelNotSupported($document);
        }
        if (null === $seoMetadata->getOriginalUrl() || '' === $seoMetadata->getOriginalUrl()) {
            $seoMetadata->setOriginalUrl($document->getSeoOriginalUrl());
        }
    }
}
