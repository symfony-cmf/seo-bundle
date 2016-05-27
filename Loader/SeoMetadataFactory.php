<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader;

use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;

/**
 * Creates a SeoMetadata object based on the content.
 *
 * This returns either an empty SeoMetadata instance or the
 * SeoMetadata instance return by getSeoMetadata() of the
 * content object.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class SeoMetadataFactory
{
    /**
     * @param object $content
     *
     * @return SeoMetadataInterface
     *
     * @throws InvalidArgumentException
     */
    public static function initializeSeoMetadata($content)
    {
        if (!$content instanceof SeoAwareInterface) {
            return new SeoMetadata();
        }

        $contentSeoMetadata = $content->getSeoMetadata();

        if ($contentSeoMetadata instanceof SeoMetadataInterface) {
            return self::copyMetadata($contentSeoMetadata);
        }

        if (null === $contentSeoMetadata) {
            $seoMetadata = new SeoMetadata();
            $content->setSeoMetadata($seoMetadata); // make sure it has metadata the next time

            return $seoMetadata;
        }

        throw new InvalidArgumentException(sprintf(
            'getSeoMetadata must return either an instance of SeoMetadataInterface or null, "%s" given',
            is_object($contentSeoMetadata) ? get_class($contentSeoMetadata) : gettype($contentSeoMetadata)
        ));
    }

    /**
     * Copy the metadata object to sanitize it and remove doctrine traces.
     *
     * @param SeoMetadataInterface $contentSeoMetadata
     *
     * @return SeoMetadata
     */
    private static function copyMetadata(SeoMetadataInterface $contentSeoMetadata)
    {
        $metadata = new SeoMetadata();

        return $metadata
            ->setTitle($contentSeoMetadata->getTitle())
            ->setMetaKeywords($contentSeoMetadata->getMetaKeywords())
            ->setMetaDescription($contentSeoMetadata->getMetaDescription())
            ->setOriginalUrl($contentSeoMetadata->getOriginalUrl())
            ->setExtraProperties($contentSeoMetadata->getExtraProperties() ?: array())
            ->setExtraNames($contentSeoMetadata->getExtraNames() ?: array())
            ->setExtraHttp($contentSeoMetadata->getExtraHttp() ?: array())
        ;
    }
}
