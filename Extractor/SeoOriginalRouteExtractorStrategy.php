<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the original route from documents
 * implementing the SeoOriginalRouteExtractorInterface.
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
        if (!$document instanceof SeoOriginalRouteExtractorInterface) {
            throw new SeoExtractorStrategyException(
                sprintf(
                    'The given document %s is not supported by this strategy. Call supports() method first.',
                    get_class($document)
                )
            );
        }
        $seoMetadata->setOriginalUrl($document->extractOriginalRoute());
    }
}
