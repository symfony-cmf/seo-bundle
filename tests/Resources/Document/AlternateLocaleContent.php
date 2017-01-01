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
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Symfony\Component\Routing\Route;

/**
 * @PHPCRODM\Document(translator="attribute")
 */
class AlternateLocaleContent extends ContentBase implements RouteReferrersInterface, TranslatableInterface
{
    /**
     * @var string
     *
     * @PHPCRODM\Locale
     */
    protected $locale;

    /**
     * @var string
     *
     * @PHPCRODM\Field(type="string",translated=true)
     */
    protected $title;

    /**
     * @var ArrayCollection|Route[]
     *
     * @PHPCRODM\Referrers(
     *  referringDocument="Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route",
     *  referencedBy="content"
     * )
     */
    protected $routes;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
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
