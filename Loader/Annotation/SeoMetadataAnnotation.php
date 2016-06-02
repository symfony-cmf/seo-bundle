<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
interface SeoMetadataAnnotation extends \Serializable
{
    /**
     * Configures the seo metadata based on the extract value.
     *
     * @param SeoMetadataInterface $seoMetadata
     * @param mixed                $value
     */
    public function configureSeoMetadata(SeoMetadataInterface $seoMetadata, $value);
}
