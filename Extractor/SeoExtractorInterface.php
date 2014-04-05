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
 * An extractor strategy updates the SeoMetadata from a document.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoExtractorInterface
{
    /**
     * Check whether the strategy supports this document.
     *
     * The decision could be based on the object implementing
     * an interface or being instance of a specific class,
     * or introspection to see if a certain method exists.
     *
     * @param object $object
     *
     * @return boolean whether this strategy supports $object
     */
    public function supports($object);

    /**
     * Update the metadata object with information from this document.
     *
     * It is up to the strategy to check if certain fields
     * are already set by previous strategies and decide on a merge strategy.
     *
     * This method is should only be called if supports returned true.
     *
     * @param object               $object
     * @param SeoMetadataInterface $seoMetadata
     */
    public function updateMetadata($object, SeoMetadataInterface $seoMetadata);
}
