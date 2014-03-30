<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the description from documents implementing the
 * SeoDescriptionReadInterface.
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
        return $document instanceof SeoDescriptionReadInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoDescriptionReadInterface $document
     */
    public function updateMetadata($document, SeoMetadataInterface $seoMetadata)
    {
        if (null === $seoMetadata->getMetaDescription() || '' === $seoMetadata->getMetaDescription()) {
           $seoMetadata->setMetaDescription($document->getSeoDescription());
        }
    }
}
