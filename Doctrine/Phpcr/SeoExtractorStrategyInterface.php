<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * Extractor strategies are responsible for deciding if a document supports that
 * specific strategy and serves a method to extract a value from a document.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoExtractorStrategyInterface
{
    /**
     * An extractor strategy should be able to
     * decide if a document support its strategy.
     *
     * It is up to you how you wanna construct that
     * decision. There will be some ExtractorInterfaces for some
     * document's properties. You can check for those interfaces
     * or check for the existence of some getter methods.
     *
     * At the moment the SeoBundle provides the following Interfaces for
     * that use case:
     *
     * - SeoTitleExtractorInterface
     * - SeoDescriptionExtractorInterface
     * - SeoOriginalRouteExtractorInterface
     *
     * @param   SeoAwareInterface $document
     * @return  boolean
     */
    public function supports(SeoAwareInterface $document);

    /**
     * If the document supports the current extractor strategy this method
     * should be able to extract a document property's value or values
     * and update the SeoMetadata.
     *
     * The following interface will provide a method, that you can call to
     * get the property value:
     *
     * - SeoTitleExtractorInterface - extractTitle()
     * - SeoDescriptionExtractorInterface - extractDescription()
     * - SeoOriginalRouteExtractorInterface - extractOriginalRoute()
     *
     * @param SeoAwareInterface $document
     * @param SeoMetadataInterface $seoMetadata
     */
    public function updateMetadata(SeoAwareInterface $document, SeoMetadataInterface $seoMetadata);
}
