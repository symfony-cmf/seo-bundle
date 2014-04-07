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
 * This strategy extracts the description from contents implementing the
 * SeoDescriptionReadInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoDescriptionExtractor implements SeoExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($content)
    {
        return $content instanceof SeoDescriptionReadInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoDescriptionReadInterface $content
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setMetaDescription($content->getSeoDescription());
    }
}
