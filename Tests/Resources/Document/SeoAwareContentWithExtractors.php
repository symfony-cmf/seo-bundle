<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContentWithExtractorsModel;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class SeoAwareContentWithExtractors extends SeoAwareContentWithExtractorsModel
{
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
}
