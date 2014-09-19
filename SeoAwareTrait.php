<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

/**
 * This trait plugs into your model to implement SeoAwareInterface
 * with brevity.
 *
 * @author Luis Cordova <cordoval@gmail.com>
 */
trait SeoAwareTrait
{
    protected $seoMetadata;

    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }

    public function setSeoMetadata($metadata)
    {
        $this->seoMetadata = $metadata;
    }
}
