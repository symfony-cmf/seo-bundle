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
}
