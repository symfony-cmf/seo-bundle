<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use PHPCR\NodeInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata as SeoMetadataModel;

class SeoMetadata extends SeoMetadataModel
{
    /**
     * @var NodeInterface
     */
    protected $node;

    /**
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