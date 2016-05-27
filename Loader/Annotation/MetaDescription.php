<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 * @Annotation
 */
class MetaDescription implements SeoMetadataAnnotation
{
    /**
     * The description length to truncate the description.
     *
     * The default value 0 disables truncation.
     *
     * @var int
     */
    public $truncate = 0;

    public function serialize()
    {
        return serialize([$this->truncate]);
    }

    public function unserialize($serialized)
    {
        list($this->truncate) = unserialize($serialized);
    }

    public function configureSeoMetadata(SeoMetadataInterface $seoMetadata, $value)
    {
        if ($this->truncate > 0 && strlen($value) > $this->truncate) {
            $value = substr($value, 0, $this->truncate).'...';
        }

        $seoMetadata->setMetaDescription($value);
    }
}
