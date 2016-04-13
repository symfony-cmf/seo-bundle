<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * An extractor strategy updates the SeoMetadata from a content.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface ExtractorInterface
{
    /**
     * Check whether the strategy supports this content.
     *
     * The decision could be based on the content implementing
     * an interface or being instance of a specific class,
     * or introspection to see if a certain method exists.
     *
     * @param object $content
     *
     * @return bool
     */
    public function supports($content);

    /**
     * Update the metadata with information from this content.
     *
     * It is up to the strategy to check if certain fields
     * are already set by previous strategies and decide on a merge strategy.
     *
     * This method should only be called if supports returned true.
     *
     * @param object               $content
     * @param SeoMetadataInterface $seoMetadata
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata);
}
