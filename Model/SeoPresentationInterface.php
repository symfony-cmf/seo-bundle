<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
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
     * @return bool|string
     */
    public function getRedirect();

    /**
     * This method is needed to get the default title parameters injected. They are used for
     * concatenating the default values and the seo meta data or defining the strategy for that.
     *
     * @param  array $titleParameters
     */
    public function setTitleParameters(array $titleParameters);

    /**
     * This method is the setter injection for the content parameters which contain strategies for
     * duplicate content.
     *
     * @param  array $contentParameters
     */
    public function setContentParameters(array $contentParameters);

    /**
     * Will need the locale to make decision on the default title to
     * have multilang support.
     *
     * @param $locale
     */
    public function setLocale($locale);
}
