<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Burgov\Bundle\KeyValueFormBundle\KeyValueContainer;
use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;

/**
 * This class is a container for the metadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadata implements SeoMetadataInterface
{
    /**
     * Id for the document.
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
     * @var string
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
    private $extraProperties = array();

    /**
     * To store extra meta tags for type name.
     *
     * @var array
     */
    private $extraNames = array();

    /**
     * To store meta tags for type http-equiv.
     *
     * @var array
     */
    private $extraHttp = array();

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * {@inheritdoc}
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtraProperties($extraProperties)
    {
        $this->extraProperties = $this->toArray($extraProperties);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtraProperties()
    {
        return $this->extraProperties;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtraProperty($key, $value)
    {
        $this->extraProperties[$key] = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExtraProperty($key)
    {
        if (array_key_exists($key, $this->extraProperties)) {
            unset($this->extraProperties[$key]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setExtraNames($extraNames)
    {
        $this->extraNames = $this->toArray($extraNames);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtraNames()
    {
        return $this->extraNames;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtraName($key, $value)
    {
        $this->extraNames[$key] = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExtraName($key)
    {
        if (array_key_exists($key, $this->extraNames)) {
            unset($this->extraNames[$key]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setExtraHttp($extraHttp)
    {
        $this->extraHttp = $this->toArray($extraHttp);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtraHttp()
    {
        return $this->extraHttp;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtraHttp($key, $value)
    {
        $this->extraHttp[$key] = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExtraHttp($key)
    {
        if (array_key_exists($key, $this->extraHttp)) {
            unset($this->extraHttp[$key]);
        }
    }

    /**
     * Extract an array out of $data or throw an exception if not possible.
     *
     * @param array|KeyValueContainer|\Traversable $data Something that can be converted to an array.
     *
     * @return array Native array representation of $data
     *
     * @throws InvalidArgumentException If $data can not be converted to an array.
     */
    private function toArray($data)
    {
        if (is_array($data)) {
            return $data;
        }

        if ($data instanceof KeyValueContainer) {
            return $data->toArray();
        }

        if ($data instanceof \Traversable) {
            return iterator_to_array($data);
        }

        throw new InvalidArgumentException(
            sprintf('Expected array, Traversable or KeyValueContainer, got "%s"',
                is_object($data) ? get_class($data) : gettype($data)));
    }
}
