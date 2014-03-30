<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * This interface is responsible to mark a document to be aware of SEO
 * metadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoAwareInterface
{
    /**
     * Gets the SEO metadata for this document.
     *
     * @return SeoMetadataInterface
     */
    public function getSeoMetadata();

    /**
     * Sets the SEO metadata for this document.
     *
     * This method is used by a listener, which converts the metadata to a 
     * plain array in order to persist it and converts it back when the object
     * is fetched.
     *
     * @param array|SeoMetadataInterface $metadata
     */
    public function setSeoMetadata($metadata);
}
