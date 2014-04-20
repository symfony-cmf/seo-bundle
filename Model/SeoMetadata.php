<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Model\Extra;

/**
 * This class is a container for the metadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadata implements SeoMetadataInterface
{
    /**
     * Node path for the document.
     */
    private $id;

    /**
     * For translatable metadata.
     */
    private $locale;

    /**
     * This string contains the information where we will find the original content.
     * Depending on the setting for the cmf_seo.original_route_pattern, it
     * will do a redirect to this url or create a canonical link with this
     * value as the href attribute.
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
     * This comma separated list will contain the keywords for the page's meta information.
     *
     * @var string
     */
    private $metaKeywords;

    /**
     * @var string
     */
    private $title;

    /**
     * To store meta tags for type property.
     *
     * @var array
     */
    private $extraProperties;

    /**
     * To store extra meta tags for type name.
     *
     * @var array
     */
    private $extraNames;

    /**
     * To store meta tags for type http-equiv.
     *
     * @var array
     */
    private $extraHttp;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * {@inheritDoc}
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtraProperties($extraProperties)
    {
        foreach($extraProperties as $extra) {
            $this->extraProperties[$extra->key] = (string)$extra->value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraProperties()
    {
        return $this->extraProperties;
    }

    /**
     * {@inheritDoc}
     */
    public function addExtraProperty(Extra $extra)
    {
        $this->extraProperties[$extra->key] = (string)$extra->value;
    }

    /**
     * {@inheritDoc}
     */
    public function removeExtraProperty(Extra $extra)
    {
        if (array_key_exists($extra->key, $this->extraProperties)) {
            unset($this->extraProperties[$extra->key]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setExtraNames($extraNames)
    {
        foreach($extraNames as $extra) {
            $this->extraNames[$extra->key] = (string)$extra->value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraNames()
    {
        return $this->extraNames;
    }

    /**
     * {@inheritDoc}
     */
    public function addExtraName(Extra $extra)
    {
        $this->extraNames[$extra->key] = (string)$extra->value;
    }

    /**
     * {@inheritDoc}
     */
    public function removeExtraName(Extra $extra)
    {
        if (array_key_exists($extra->key, $this->extraNames)) {
            unset($this->extraNames[$extra->key]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setExtraHttp($extraHttp)
    {
        foreach($extraHttp as $extra) {
            $this->extraHttp[$extra->key] = (string)$extra->value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtraHttp()
    {
        return $this->extraHttp;
    }

    /**
     * {@inheritDoc}
     */
    public function addExtraHttp(Extra $extra)
    {
        $this->extraHttp[$extra->key] = (string)$extra->value;
    }

    /**
     * {@inheritDoc}
     */
    public function removeExtraHttp(Extra $extra)
    {
        if (array_key_exists($extra->key, $this->extraHttp)) {
            unset($this->extraHttp[$extra->key]);
        }
    }
}
