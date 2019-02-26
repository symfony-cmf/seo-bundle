<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
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
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return $content instanceof OriginalUrlReadInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setOriginalUrl($content->getSeoOriginalUrl());
    }
}
