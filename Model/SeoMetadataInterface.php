<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * The interface for the SeoMetadata.
 *
 * This class should have getter and setter as well.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoMetadataInterface
{
    /**
     * Setter for the description
     *
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription);

    /**
     * Getter for the description, shown in a meta tag
     *
     * @return string
     */
    public function getMetaDescription();

    /**
     * Setter for the Keywords
     *
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * Getter for the Keywords that are shown in a meta tag
     *
     * @return string
     */
    public function getMetaKeywords();

    /**
     * Setter for the originalUrl
     *
     * @param string $originalUrl
     */
    public function setOriginalUrl($originalUrl);

    /**
     * Getter for the original url, means the url where to redirectResponse
     * or setting href property of the canonical link. This depends on the value
     * of the originalUrl strategy.
     *
     * @return string
     */
    public function getOriginalUrl();

    /**
     * Setter for the seo title
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Getter for the seo title
     *
     * @return string
     */
    public function getTitle();

    /**
     * for the process of serialization for storing the seo metadata we
     * need to get all properties in an array
     *
     * @return array
     */
    public function toArray();
}
