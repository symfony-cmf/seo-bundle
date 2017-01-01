<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\SitemapAwareInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Symfony\Component\Routing\Route;

/**
 * @PHPCRODM\Document(referenceable=true, translator="attribute")
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapAwareContent extends ContentBase implements RouteReferrersReadInterface, TranslatableInterface, SitemapAwareInterface
{
    /**
     * @var string
     *
     * @PHPCRODM\Locale
     */
    protected $locale;

    /**
     * @var ArrayCollection|Route[]
     *
     * @PHPCRODM\Referrers(
     *  referringDocument="Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route",
     *  referencedBy="content"
     * )
     */
    protected $routes;

    /**
     * @var bool
     *
     * @PHPCRODM\Field(type="boolean",property="visible_for_sitemap")
     */
    private $isVisibleForSitemap;

    /**
     * @var string
     *
     * @PHPCRODM\Field(type="string",translated=true)
     */
    protected $title;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
    }

    /**
     * @param string $sitemap
     *
     * @return bool
     */
    public function isVisibleInSitemap($sitemap)
    {
        return $this->isVisibleForSitemap;
    }

    /**
     * @param bool $isVisibleForSitemap
     *
     * @return SitemapAwareContent
     */
    public function setIsVisibleForSitemap($isVisibleForSitemap)
    {
        $this->isVisibleForSitemap = $isVisibleForSitemap;

        return $this;
    }

    /**
     * Add a route to the collection.
     *
     * @param Route $route
     */
    public function addRoute($route)
    {
        $this->routes->add($route);
    }

    /**
     * Remove a route from the collection.
     *
     * @param Route $route
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * Get the routes that point to this content.
     *
     * @return Route[] Route instances that point to this content
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return string|bool the locale of this model or false if
     *                     translations are disabled in this project
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string|bool $locale the local for this model, or false if
     *                            translations are disabled in this project
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
