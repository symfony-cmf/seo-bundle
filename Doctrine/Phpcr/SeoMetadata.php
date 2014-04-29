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

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadata extends SeoMetadataModel
{
    /**
     * The documents phpcr node.
     */
    protected $node;

    /**
     * The node's name.
     */
    protected $name;

    /**
     * The node's parent document.
     */
    protected $parentDocument;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param NodeInterface $node
     */
    public function setNode($node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * @return NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param NodeInterface $parentDocument
     */
    public function setParentDocument($parentDocument)
    {
        $this->parentDocument = $parentDocument;

        return $this;
    }

    /**
     * @return NodeInterface
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }
}
