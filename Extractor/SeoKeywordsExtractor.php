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
 * This strategy extracts the keywords from documents implementing the
 * SeoKeywordsReadInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoKeywordsExtractor implements SeoExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($object)
    {
        return $object instanceof SeoKeywordsReadInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param SeoKeywordsReadInterface $object
     */
    public function updateMetadata($object, SeoMetadataInterface $seoMetadata)
    {
        $keywords = $object->getSeoKeywords();
        if (null === $keywords || '' === $keywords) {

            if (is_array($keywords)) {
                $keywords = implode(', ', $keywords);
            }
            $seoMetadata->setMetaKeywords($keywords);
        }
    }
}
