<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * This trait plugs into your model to implement SeoAwareInterface
 * with brevity.
 *
 * @author Luis Cordova <cordoval@gmail.com>
 */
trait SeoAwareTrait
{
    /**
     * @PHPCRODM\Child
     */
    private $seoMetadata;

    /**
     * {@inheritdoc}
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function setSeoMetadata($metadata)
    {
        $this->seoMetadata = $metadata;
    }
}
