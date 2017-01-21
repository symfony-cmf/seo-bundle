<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\HierarchyInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata as SeoMetadataModel;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadata extends SeoMetadataModel implements HierarchyInterface, TranslatableInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var object
     */
    protected $parentDocument;

    /**
     * @param string $name
     *
     * @return SeoMetadata
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
     * {@inheritdoc}
     */
    public function setParentDocument($parentDocument)
    {
        $this->parentDocument = $parentDocument;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * @deprecated For compatibility with Doctrine PHPCR ODM 1.4, to be removed in version 3.0.
     * @see setParentDocument
     */
    public function setParent($parent)
    {
        @trigger_error('The '.__METHOD__.'() method is deprecated and will be removed in version 3.0. Use setParentDocument() instead.', E_USER_DEPRECATED);

        return $this->setParentDocument($parent);
    }

    /**
     * @deprecated For compatibility with Doctrine PHPCR ODM 1.4, to be removed in version 3.0.
     * @see getParentDocument
     */
    public function getParent()
    {
        @trigger_error('The '.__METHOD__.'() method is deprecated and will be removed in version 3.0. Use getParentDocument() instead.', E_USER_DEPRECATED);

        return $this->getParentDocument();
    }
}
