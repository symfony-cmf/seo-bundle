<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;

/**
 * This class is a container for the metadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadata implements SeoMetadataInterface
{
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
     * To store extra properties.
     *
     * @var Collection
     */
    private $extraProperties;

    public function __construct()
    {
        $this->extraProperties = new ArrayCollection();
    }

    public static function createFromArray(array $data)
    {
        $keys = array('title', 'metaDescription', 'metaKeywords', 'originalUrl');
        $metadata = new self();
        foreach ($data as $key => $value) {
            $metadata->createProperty($metadata, $key, $value);

            if (!in_array($key, $keys)) {
                continue;
            }

            $metadata->{'set'.ucfirst($key)}($value);
        }

        return $metadata;
    }

    /**
     * A helper for the construction process.
     *
     * This method checks if the types are allowed and creates a meta property
     * from the type, key and value.
     *
     * @param SeoMetadataInterface $metadata
     * @param string               $persistedKey
     * @param string               $persistedValue
     */
    public function createProperty(SeoMetadataInterface $metadata, $persistedKey, $persistedValue)
    {
        $type = array_filter(ExtraProperty::getAllowedTypes(), function($possibleType) use ($persistedKey) {
            return !strncmp($persistedKey, $possibleType, strlen($possibleType));
        });

        if (!$type || !is_array($type)) {
            return;
        }

        $type = reset($type);
        $metadata->addExtraProperty(new ExtraProperty(substr($persistedKey, strlen($type.'_')), $persistedValue, $type));
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
    public function setExtraProperties(Collection $extraProperties)
    {
        $this->extraProperties = $extraProperties;
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
    public function addExtraProperty(ExtraProperty $property)
    {
        $this->extraProperties->add($property);
    }

    /**
     * {@inheritDoc}
     */
    public function removeExtraProperty(ExtraProperty $property)
    {
        $this->extraProperties->removeElement($property);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return array_merge(
            array(
                'title'           => $this->getTitle() ?: '',
                'metaDescription' => $this->getMetaDescription() ?: '',
                'metaKeywords'    => $this->getMetaKeywords() ?: '',
                'originalUrl'     => $this->getOriginalUrl() ?: '',
            ),
            $this->getExtraPropertiesArray()
        );
    }

    /**
     * All extra properties will be added to a flat array
     * to persist them with an assoc mapping.
     *
     * This method just creates an array of them with keys that are
     * prefixed with "property_", "http-equiv_" or "name_".
     *
     * @return array
     */
    private function getExtraPropertiesArray()
    {
        /** @var ExtraProperty[] $properties */
        $properties = $this->extraProperties->toArray();
        $result = array();

        foreach ($properties as $property) {
            $result[$property->getType().'_'.$property->getKey()] = $property->getValue();
        }

        return $result;
    }
}
