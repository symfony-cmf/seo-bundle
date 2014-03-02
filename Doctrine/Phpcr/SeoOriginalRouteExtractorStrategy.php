<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This extractor strategy is responsible for extracting
 * ths documents original route (if have lots of them and
 * has be aware of duplicate content).
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoOriginalRouteExtractorStrategy implements SeoExtractorStrategyInterface
{

    /**
     * {@inheritDoc}
     */
    public function supports(SeoAwareInterface $document)
    {
        return $document instanceof SeoOriginalRouteExtractorInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoOriginalRouteExtractorInterface $document
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setOriginalUrl($document->extractOriginalRoute());
    }
}
