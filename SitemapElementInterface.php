<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

/**
 * Models implementing this interface can indicate whether they should be listed in the sitemap or not.
 *
 * For PHPCR-ODM documents used with the \Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapUrlInformationProvider,
 * make sure to call the property that stores this information "visible_for_sitemap",
 * as the provider uses the property in the query rather than loading all documents and checking the method.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SitemapElementInterface
{
    /**
     * Decision whether a document should be visible
     * in sitemap or not.
     *
     * @return bool
     */
    public function isVisibleInSitemap();
}
