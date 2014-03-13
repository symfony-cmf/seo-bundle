<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * This class is a container for the metadata.
 *
 * This data will be served to sonatas PageService if the
 * document implements the SeoAwareInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadata implements SeoMetadataInterface
{
    /**
     * This string contains the information where we will find the original content.
     * Depending on the setting for the cmf_seo.content.pattern, we will do an redirect to this url or
     * create a canonical link with this value as the href attribute.
     *
     * @var string
     */
    private $originalUrl;

    /**
     * If this string is set, it will be inserted as a meta tag for the page description.
     *
     * @var  string
     */
    private $metaDescription;

    /**
     * This comma separated list will contain the Keywords for the page's meta information.
     *
     * @var string
     */
    private $metaKeywords;

    /**
     * Depending on the cmf_seo.title.pattern this string will be prepend/append to a
     * default title or will replace it. The default title is can be (multi lang) set
     * in in the configuration under cmf_seo.title.default.
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
     * Just to get a string representation of the object.
     * @todo have a look if i still need it that way
     *
     * @return string
     */
    public function __toString()
    {
        return serialize($this);
    }

    /**
     * The properties of this class are stored as a field with assoac="true". To do so, this method
     * will serve all values as an array.
     *
     * @return array
     */
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
