<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;

class RouteStrategyDocument implements
    SeoOriginalRouteInterface,
    SeoAwareInterface
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
}
