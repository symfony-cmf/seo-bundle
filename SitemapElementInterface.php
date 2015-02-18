<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


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
