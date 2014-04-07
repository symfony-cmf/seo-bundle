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
 * This strategy extracts the title from documents with a getTitle() method.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class TitleReadExtractor implements SeoExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($object)
    {
        return method_exists($object, 'getTitle');
    }

    /**
     * {@inheritDoc}
     */
    public function updateMetadata($object, SeoMetadataInterface $seoMetadata)
    {
        $seoMetadata->setTitle($object->getTitle());
    }
}
