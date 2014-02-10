<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;


interface SeoPresentationInterface
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

    /**
     * will return false if the strategy for duplicate content is not redirect, or it is
     * but there is not redirect route in the meta data
     *
     * @return bool | string
     */
    public function getRedirect();
}
