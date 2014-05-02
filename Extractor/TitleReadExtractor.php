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
 * This strategy extracts the title from content with a getTitle() method.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class TitleReadExtractor implements ExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($content)
    {
        return method_exists($content, 'getTitle');
    }

    /**
     * {@inheritDoc}
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        if (!$seoMetadata->getTitle()) {
            $seoMetadata->setTitle($content->getTitle());
        }
    }
}
