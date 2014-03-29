<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use PHPCR\NodeInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareContent as SeoAwareContentModel;

/**
 * The bundle's own content class which supports the SeoAwareInterface.
 *
 * This interface is responsible for serving the SeoMeta and being recognised
 * by the sonata admin extension.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoAwareContent extends SeoAwareContentModel
{
    /**
     * The PHPCR node.
     *
     * @var NodeInterface
     */
    protected $node;

    /**
     * The parent document.
     *
     * @var object
     */
    protected $parentDocument;

    /**
     * The local name of the node the document belongs to.
     *
     * @var string
     */
    protected $name;

    /**
     * Get the underlying PHPCR node of this content.
     *
     * @return NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Set the parent document.
     *
     * @param object $parent The parent document.
     */
    public function setParentDocument($parent)
    {
        $this->parentDocument = $parent;
    }

    /**
     * Get the parent document.
     *
     * @return object
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * Set the document name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the document name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
