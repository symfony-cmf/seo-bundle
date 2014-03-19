<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteKeyInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;

class RouteStrategyDocument implements
    SeoOriginalRouteInterface,
    SeoAwareInterface,
    SeoOriginalUrlInterface,
    SeoOriginalRouteKeyInterface
{

    /**
     * Provide the original url of this page to be used in SEO context.
     *
     * @return Route the original route.
     */
    public function getSeoOriginalRoute()
    {

    }

    /**
     * To let a content be seo aware means in the SeoBundle to serve the SeoMetadata.
     * This SeoMetadata contains the information to fill some meta tags or has
     * the information of the original url of the content.
     *
     * @return SeoMetadata
     */
    public function getSeoMetadata()
    {
        // TODO: Implement getSeoMetadata() method.
    }

    /**
     * The method returns the absolute url as a string to redirect to
     * or set to the canonical link.
     *
     * @return string
     */
    public function getSeoOriginalUrl()
    {
        // TODO: Implement getSeoOriginalUrl() method.
    }

    /**
     * This method returns the symfony route key as a string.
     *
     * @return string
     */
    public function getSeoOriginalRouteKey()
    {
        // TODO: Implement getSeoOriginalRouteKey() method.
    }
}
