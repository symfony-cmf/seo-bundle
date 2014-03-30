<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Routing\Route;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;

class SeoAwareContent implements
    SeoAwareInterface,
    TranslatableInterface,
    RouteReferrersInterface
{
    /**
     * Primary identifier, details depend on storage layer.
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var SeoMetadataInterface
     */
    protected $seoMetadata;

    /**
     * A collection of route documents.
     *
     * @var Collection
     */
    protected $routes;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var string
     */
    private $locale;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Any content model can handle its seo properties. By implementing
     * this interface a model has to return its class for all the seo properties.
     *
     * @return SeoMetadataInterface
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }

    /**
     * @param SeoMetadataInterface $seoMetadata
     */
    public function setSeoMetadata($seoMetadata)
    {
        $this->seoMetadata= $seoMetadata;
    }

    /**
     * @return string|boolean The locale of this model or false if
     *                        translations are disabled in this project.
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string|boolean $locale The local for this model, or false if
     *                               translations are disabled in this project.
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @param Route $route
     */
    public function addRoute($route)
    {
        $this->routes->add($route);
    }

    /**
     * @param Route $route
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * @return Route[] The routes that point to this content
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * need to convert the object into an array, which can be persisted in
     * with the phpcr
     * @todo write issue to persist objects same way like arrays
     */
    public function preFlush()
    {
        $this->seoMetadata = $this->seoMetadata instanceof SeoMetadataInterface
            ? $this->seoMetadata->toArray()
            : array();
    }

    /**
     * Sets the information back to a SeoMetadata object to make it easier to use.
     */
    public function postLoad()
    {
        $persistedData = $this->seoMetadata;
        $this->seoMetadata = new SeoMetadata();
        foreach ($persistedData as $property => $value) {
            if (method_exists($this->seoMetadata, 'set' . ucfirst($property))) {
                $this->seoMetadata->{'set' . ucfirst($property)}($value);
            }
        }
    }
}
