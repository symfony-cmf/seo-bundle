<?php

namespace Cmf\Bundle\SeoBundleModel;

class SeoMetadata implements SeoMetadataInterface
{
    /**
     * This type describes the strategy of the way to solve duplicate content problems.
     * This can be done either by using a redirect or a canonical
     * Means: this property can have two values only canonical or redirect.
     *
     * @var string
     */
    private $originalUrlStrategy;

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
     * This property will be used for the page title depending on the titleStrategy.
     *
     * @var string
     */
    private $title;

    /**
     * this string will provide a strategy for setting the title. The following values are allowed:
     *
     * prepend - Will prepend the value to a default one from the config
     * append - will append the value to a default one from the config
     * replace - will set the value instead of the default value from the config
     *
     * @var string
     */
    private $titleStrategy;

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
     * @param  string                                             $originalUrlStrategy
     * @throws \Cmf\Bundle\SeoBundleExceptions\SeoAwareContentException
     * @todo manage a default value by config
     */
    public function setOriginalUrlStrategy($originalUrlStrategy)
    {
        if (!in_array($originalUrlStrategy, array('canonical', 'redirect'))) {
            $this->originalUrlStrategy = 'canonical';
        }
        $this->originalUrlStrategy = $originalUrlStrategy;
    }

    /**
     * @return string
     */
    public function getOriginalUrlStrategy()
    {
        return $this->originalUrlStrategy;
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
     * @param  string                                             $titleStrategy
     * @throws \Cmf\Bundle\SeoBundleExceptions\SeoAwareContentException
     * @todo manage a default value by config
     */
    public function setTitleStrategy($titleStrategy)
    {
        if (!in_array($titleStrategy, array('prepend', 'append', 'replace'))) {
            $this->titleStrategy = 'prepend';
        }
        $this->titleStrategy = $titleStrategy;
    }

    /**
     * @return string
     */
    public function getTitleStrategy()
    {
        return $this->titleStrategy;
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
            'titleStrategy'         => $this->getTitleStrategy(),
            'metaDescription'       => $this->getMetaDescription(),
            'metaKeywords'          => $this->getMetaKeywords(),
            'originalUrl'           => $this->getOriginalUrl(),
            'originalUrlStrategy'   => $this->getOriginalUrlStrategy()
        );

    }
}
