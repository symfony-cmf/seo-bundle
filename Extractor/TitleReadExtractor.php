<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\ModelNotSupportedException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

class TitleReadExtractor implements SeoExtractorInterface
{

    /**
     * {@inheritDoc}
     */
    public function supports($document)
    {
        return method_exists($document, 'getTitle');
    }

    /**
     * {@inheritDoc}
     */
    public function updateMetadata($document, SeoMetadataInterface $seoMetadata)
    {
        if (!method_exists($document, 'getTitle')) {
            throw new ModelNotSupportedException($document);
        }

        if (null === $seoMetadata->getTitle() || '' === $seoMetadata->getTitle()) {
            $seoMetadata->setTitle($document->getTitle());
        }
    }
}
