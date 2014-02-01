<?php

namespace Cmf\SeoBundle\Document\PHPCR;

use Cmf\SeoBundle\Model\SeoAwareContentInterface;
use Cmf\SeoBundle\Model\SeoStuff;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Symfony\Component\Routing\Route;

/**
 * @todo create an own document instead of using the static content one
 *
 * Class SeoAwareContent
 * @package Cmf\SeoBundle\Doctrine\Phpcr
 */
class SeoAwareContent implements
    SeoAwareContentInterface,
    RouteReferrersInterface {

    /**
     * Primary identifier, details depend on storage layer.
     */
    protected $id;

    protected $node;

    protected $parent;

    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var SeoStuff
     */
    protected $seoStuff;

    /**
     * @var RouteObjectInterface[]
     */
    protected $routes;



    /**
     * Explicitly set the primary id, if the storage layer permits this.
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

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
     * this interface a model has to return its class for all the seo properties
     * @todo find a better documentation
     *
     * @return SeoStuff
     */
    public function getSeoStuff()
    {
        return $this->seoStuff;
    }

    /**
     * @param SeoStuff $seoStuff
     */
    public function setSeoStuff($seoStuff)
    {
        $this->seoStuff = $seoStuff;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return mixed
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * need to convert the object into an array, which can be persisted in
     * with the phpcr
     * @todo write issue to persist objects same way like arrays
     */
    public function preFlush()
    {
        $this->seoStuff = $this->seoStuff->toArray();
    }

    /**
     * wil set the information back to the seoStuff object to handle it easier
     */
    public function postLoad()
    {
        $persistedData = $this->seoStuff;
        $this->seoStuff = new SeoStuff();
        foreach ($persistedData as $property => $value) {
            $this->seoStuff->{'set' . ucfirst($property)}($value);
        }
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
     * @return \Symfony\Component\Routing\Route[] Route instances that point to this content
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}