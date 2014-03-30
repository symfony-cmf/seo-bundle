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

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleReadInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class ContentWithExtractors extends ContentBase implements
    SeoTitleReadInterface,
    SeoDescriptionReadInterface,
    SeoOriginalUrlReadInterface
{
    /**
     * Provide a title of this page to be used in SEO context.
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->getTitle();
    }

    /**
     * Provide a description of this page to be used in SEO context.
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return substr($this->getBody(), 0, 200).' ...';
    }

    /**
     * The method returns the absolute url as a string to redirect to
     * or set to the canonical link.
     *
     * @return string
     */
    public function getSeoOriginalUrl()
    {
        return '/home';
    }
}
