<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * The interface for the SeoMetadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoMetadataInterface
{
    /**
     * Update the description.
     *
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription);

    /**
     * Get the description for the meta tag.
     *
     * @return string
     */
    public function getMetaDescription();

    /**
     * Set the keywords.
     *
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * Get the Keywords for the meta tag
     *
     * @return string
     */
    public function getMetaKeywords();

    /**
     * Set the original URL for content that has several URLs.
     *
     * @param string $originalUrl
     */
    public function setOriginalUrl($originalUrl);

    /**
     * Get the original URL of this content.
     *
     * This will be used for the canonical link or to redirect to the original
     * URL, depending on your settings.
     *
     * @return string
     */
    public function getOriginalUrl();

    /**
     * Set the title.
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * for the process of serialization for storing the seo metadata we
     * need to get all properties in an array
     *
     * @return array with all fields
     */
    public function toArray();
}
