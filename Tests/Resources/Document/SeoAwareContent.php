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

use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class SeoAwareContent extends ContentBase implements SeoAwareInterface
{
    /**
     * @PHPCRODM\Child(nodeName="seo-metadata")
     */
    protected $seoMetadata;

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
     * {@inheritDoc}
     */
    public function setSeoMetadata($seoMetadata)
    {
        $this->seoMetadata = $seoMetadata;

        return $this;
    }
}
