<?php

namespace Cmf\SeoBundle\Services;

use Cmf\SeoBundle\Model\SeoMetadataInterface;

interface CmfSeoPageInterface
{
    /**
     * simple setter for the seo meta data to the service
     * @param SeoMetadataInterface $seoMetadata
     */
    public function setSeoMetadata(SeoMetadataInterface $seoMetadata);

    /**
     * Just a method which will set the values to the sonata service or trigger some other
     * action like the redirect
     *
     */
    public function setMetadataValues();
}
