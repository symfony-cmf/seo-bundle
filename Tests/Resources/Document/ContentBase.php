<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Doctrine\ORM\Mapping as ORM;

/**
 * @PHPCRODM\MappedSuperclass(referenceable=true)
 * @ORM\MappedSuperclass
 */
class ContentBase
{
    /**
     * @PHPCRODM\Id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @PHPCRODM\Node
     */
    protected $node;

    /**
     *  @PHPCRODM\ParentDocument
     */
    protected $parentDocument;

    /**
     * @PHPCRODM\Nodename
     */
    protected $name;

    /**
     * @PHPCRODM\String
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @PHPCRODM\String
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the underlying PHPCR node of this content.
     *
     * @return NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Sets the parent document.
     *
     * @param object $parent The parent document.
     */
    public function setParentDocument($parent)
    {
        $this->parentDocument = $parent;

        return $this;
    }

    /**
     * Gets the parent document.
     *
     * @return object
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * Sets the document name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the document name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

        return $this;
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

        return $this;
    }
}
