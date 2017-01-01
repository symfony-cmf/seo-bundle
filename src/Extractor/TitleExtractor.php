<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the title from content implementing the
 * TitleReadInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class TitleExtractor implements ExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return $content instanceof TitleReadInterface;
    }

    /**
     * {@inheritdoc}
     *
     * @param TitleReadInterface $content
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setTitle($content->getSeoTitle());
    }
}
