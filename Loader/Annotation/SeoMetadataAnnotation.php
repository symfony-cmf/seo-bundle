<?php

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
