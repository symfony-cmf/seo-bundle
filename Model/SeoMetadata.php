<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

class SeoMetadata implements SeoMetadataInterface
{
    /**
     * This string contains the information where we will find the original content.
     * Depending on the setting for the originalUrlType, we will do an redirect to this url or
     * create a canonical link with this value as the href attribute.
     *
     * @var string
     */
    private $originalUrl;

    /**
     * If this string is set, it will be inserted as a meta tag for the page description
     *
     * @var  string
     */
    private $metaDescription;

    /**
     * This comma separated list will contain the Keywords for the page's meta information
     * @var string
     */
    private $metaKeywords;

    /**
     * depending on the strategy setting for the title this string will be prepend/append to a
     * default title or will replase it
     *
     * @var string
     */
    private $title;

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $originalUrl
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }

    /**
     * @return string
     */
    public function getOriginalUrl()
    {
        return $this->originalUrl;
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
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * to store the value of this object into databases this method will serialize the complete object
     *
     * @return string
     */
    public function __toString()
    {
        return serialize($this);
    }

    public function toArray()
    {
        return array(
            'title'                 => $this->getTitle(),
            'metaDescription'       => $this->getMetaDescription(),
            'metaKeywords'          => $this->getMetaKeywords(),
            'originalUrl'           => $this->getOriginalUrl()
        );

    }
}
