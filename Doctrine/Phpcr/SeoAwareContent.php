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
     */
    protected $name;

    /**
     * @return mixed
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param object $parent The parent document.
     */
    public function setParentDocument($parent)
    {
        $this->parentDocument = $parent;
    }

    /**
     * @return object
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
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
}
