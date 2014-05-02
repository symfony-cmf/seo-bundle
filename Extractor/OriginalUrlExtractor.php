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
 * This extractor sets the absolute URL on the SeoMetadata.
 *
 * If you have a Symfony Route, use the OriginalRouteExtractor.
 *
 * @author Maximilian Berghoff <maximilian.berghoff@gmx.de>
 */
class OriginalUrlExtractor implements ExtractorInterface
{

    /**
     * {@inheritDoc}
     */
    public function supports($content)
    {
        return $content instanceof OriginalUrlReadInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setOriginalUrl($content->getSeoOriginalUrl());
    }
}
