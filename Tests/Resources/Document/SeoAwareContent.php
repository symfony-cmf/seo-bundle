<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class SeoAwareContent extends ContentBase implements SeoAwareInterface
{
    /**
     * @PHPCRODM\String(assoc="", nullable=true)
     */
    protected $seoMetadata;
    
    /**
     * Any content model can handle its seo properties. By implementing
     * this interface a model has to return its class for all the seo properties.
     *
     * @return SeoMetadataInterface
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }

    /**
     * @param SeoMetadataInterface $seoMetadata
     */
    public function setSeoMetadata(SeoMetadataInterface $seoMetadata)
    {
        $this->seoMetadata= $seoMetadata;
    }

    /**
     * @PHPCRODM\PreUpdate
     */
    public function preUpdate()
    {
        $this->seoMetadata = $this->seoMetadata instanceof SeoMetadataInterface
            ? $this->seoMetadata->toArray()
            : array();
    }

    /**
     * @PHPCRODM\PrePersist
     */
    public function prePersist()
    {
        $this->preUpdate();
    }

    /**
     * @PHPCRODM\PostLoad
     */
    public function postLoad()
    {
        $persistedData = $this->seoMetadata;
        $this->seoMetadata = new SeoMetadata();
        foreach ($persistedData as $property => $value) {
            if (method_exists($this->seoMetadata, 'set' . ucfirst($property))) {
                $this->seoMetadata->{'set' . ucfirst($property)}($value);
            }
        }
    }
    
}
