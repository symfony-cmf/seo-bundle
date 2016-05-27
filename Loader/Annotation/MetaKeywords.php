<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 * @Annotation
 */
class MetaKeywords implements SeoMetadataAnnotation
{
    public function serialize()
    {
        return '';
    }

    public function unserialize($serialized)
    {
    }

    public function configureSeoMetadata(SeoMetadataInterface $seoMetadata, $value)
    {
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        }

        $seoMetadata->setMetaKeywords(implode(', ', (array) $value));
    }
}
