<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * The interface is responsible to mark a document as a seo aware content.
 *
 * Seo aware content stores a whole SeoMetadataInterface instance.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoAwareInterface
{

    /**
     * The SeoMetadata contains the information to fill some meta tags and/or
     * provides the original url of the content.
     *
     * @return SeoMetadataInterface
     */
    public function getSeoMetadata();
}
