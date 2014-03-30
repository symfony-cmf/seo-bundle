<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\MappedSuperclass(referenceable=true)
 */
class SeoAwareContentWithExtractorsModel implements
    SeoAwareInterface,
    SeoTitleInterface,
    SeoDescriptionInterface,
    SeoOriginalUrlInterface
{
    /**
     * @PHPCRODM\Id
     */
    protected $id;

    /**
     * @PHPCRODM\String
     */
    protected $title;

    /**
     * @PHPCRODM\String
     */
    protected $body;

    /**
     * @PHPCRODM\String(assoc="", nullable=true)
     */
    protected $seoMetadata;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

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
    public function setSeoMetadata($seoMetadata)
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

    /**
     * Provide a title of this page to be used in SEO context.
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->getTitle();
    }

    /**
     * Provide a description of this page to be used in SEO context.
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return substr($this->getBody(), 0, 200).' ...';
    }

    /**
     * The method returns the absolute url as a string to redirect to
     * or set to the canonical link.
     *
     * @return string
     */
    public function getSeoOriginalUrl()
    {
        $seoOriginalUrl = $this->getSeoMetadata()->getOriginalUrl();

        return null === $seoOriginalUrl || '' === $seoOriginalUrl ? '/home' : $seoOriginalUrl;
    }
}