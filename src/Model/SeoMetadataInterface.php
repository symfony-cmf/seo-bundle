<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * The interface for the SeoMetadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoMetadataInterface
{
    /**
     * Updates the description.
     *
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription);

    /**
     * Gets the description for the meta tag.
     *
     * @return string
     */
    public function getMetaDescription();

    /**
     * Sets the keywords.
     *
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * Gets the Keywords for the meta tag.
     *
     * @return string
     */
    public function getMetaKeywords();

    /**
     * Sets the original URL for content that has several URLs.
     *
     * @param string $originalUrl
     */
    public function setOriginalUrl($originalUrl);

    /**
     * Gets the original URL of this content.
     *
     * This will be used for the canonical link or to redirect to the original
     * URL, depending on your settings.
     *
     * @return string
     */
    public function getOriginalUrl();

    /**
     * Sets the title.
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * @param array|\Traversable|KeyValueContainer
     */
    public function setExtraProperties($extraProperties);

    /**
     * @param array|\Traversable|KeyValueContainer
     */
    public function setExtraNames($extraNames);

    /**
     * @param array|\Traversable|KeyValueContainer
     */
    public function setExtraHttp($extraHttp);

    /**
     * @return array
     */
    public function getExtraProperties();

    /**
     * @return array
     */
    public function getExtraNames();

    /**
     * @return array
     */
    public function getExtraHttp();

    /**
     * Add a key-value pair for meta attribute property.
     *
     * @param string $key
     * @param string $value
     */
    public function addExtraProperty($key, $value);

    /**
     * Add a key-value pair for meta attribute name.
     *
     * @param string $key
     * @param string $value
     */
    public function addExtraName($key, $value);

    /**
     * Add a key-value pair for meta attribute http-equiv.
     *
     * @param string $key
     * @param string $value
     */
    public function addExtraHttp($key, $value);
}
