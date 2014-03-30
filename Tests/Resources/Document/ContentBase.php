<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\MappedSuperclass(referenceable=true)
 */
class ContentBase
{
    /**
     * @PHPCRODM\Id
     */
    protected $id;

    /**
     *  @PHPCRODM\Node
     */
    protected $node;

    /**
     *  @PHPCRODM\ParentDocument
     */
    protected $parentDocument;

    /**
     *  @PHPCRODM\Nodename
     */
    protected $name;

    /**
     * @PHPCRODM\String
     */
    protected $title;

    /**
     * @PHPCRODM\String
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
}
