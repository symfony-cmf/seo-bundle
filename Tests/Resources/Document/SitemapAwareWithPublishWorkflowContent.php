<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\SeoBundle\SitemapElementInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;

/**
 * @PHPCRODM\Document(referenceable=true)
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapAwareWithPublishWorkflowContent extends ContentBase implements
    PublishableInterface,
    PublishTimePeriodInterface,
    RouteReferrersReadInterface,
    SitemapElementInterface
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
     * @var boolean whether this content is publishable
     *
     * @PHPCRODM\Field(type="boolean")
     */
    protected $publishable = true;

    /**
     * @var \DateTime|null publication start time
     */
    protected $publishStartDate;

    /**
     * @var \DateTime|null publication end time
     */
    protected $publishEndDate;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
    }

    /**
     * @return boolean
     */
    public function isVisibleInSitemap()
    {
        return $this->isVisibleForSitemap;
    }

    /**
     * @param boolean $isVisibleForSitemap
     *
     * @return SitemapAwareContent
     */
    public function setIsVisibleForSitemap($isVisibleForSitemap)
    {
        $this->isVisibleForSitemap = $isVisibleForSitemap;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setPublishable($publishable)
    {
        return $this->publishable = (bool) $publishable;
    }

    /**
     * {@inheritDoc}
     */
    public function isPublishable()
    {
        return $this->publishable;
    }

    /**
     * {@inheritDoc}
     */
    public function getPublishStartDate()
    {
        return $this->publishStartDate;
    }

    /**
     * {@inheritDoc}
     */
    public function setPublishStartDate(\DateTime $publishStartDate = null)
    {
        $this->publishStartDate = $publishStartDate;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPublishEndDate()
    {
        return $this->publishEndDate;
    }

    /**
     * {@inheritDoc}
     */
    public function setPublishEndDate(\DateTime $publishEndDate = null)
    {
        $this->publishEndDate = $publishEndDate;

        return $this;
    }

    /**
     * Get the routes that point to this content.
     *
     * @return \Symfony\Component\Routing\Route[] Route instances that point to this content
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
