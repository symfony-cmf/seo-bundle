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
use Symfony\Cmf\Bundle\SeoBundle\SitemapAwareInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Symfony\Component\Routing\Route;

/**
 * @PHPCRODM\Document(referenceable=true, mixins={"mix:lastModified"})
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class SitemapAwareWithLastModifiedDateContent extends ContentBase implements RouteReferrersReadInterface, SitemapAwareInterface
{
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
     * @var \DateTime
     * @PHPCRODM\Field(type="date", property="jcr:lastModified")
     */
    private $lastModified;

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
     * {@inheritdoc}
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param \DateTime $lastModified
     *
     * @return SitemapAwareWithLastModifiedDateContent
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }
}
