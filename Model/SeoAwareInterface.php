<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * A listener on the kernel.request event will parse documents which
 * imports this interface and calling a presentation model
 * to put the SeoMetadata into sonatas PageService.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoAwareInterface
{

    /**
     * To let a content be seo aware means in the SeoBundle to serve the SeoMetadata.
     * This SeoMetadata contains the information to fill some meta tags or has
     * the information of the original url of the content.
     *
     * @return SeoMetadata
     */
    public function getSeoMetadata();
}
