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
     * Gets the Keywords for the meta tag
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
     * Returns the array representation of the metadata properties.
     *
     * This is needed for the process of serialization of the seo metadata.
     *
     * @return array
     */
    public function toArray();

    public static function createFromArray(array $data);
}
