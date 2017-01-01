<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This interface is responsible to mark a content to be aware of SEO
 * metadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoAwareInterface
{
    /**
     * Gets the SEO metadata for this content.
     *
     * @return SeoMetadataInterface
     */
    public function getSeoMetadata();

    /**
     * Sets the SEO metadata for this content.
     *
     * This method is used by a listener, which converts the metadata to a
     * plain array in order to persist it and converts it back when the content
     * is fetched.
     *
     * @param array|SeoMetadataInterface $metadata
     */
    public function setSeoMetadata($metadata);
}
