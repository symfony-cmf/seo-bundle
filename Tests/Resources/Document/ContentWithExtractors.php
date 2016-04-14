<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\DescriptionReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\KeywordsReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\OriginalUrlReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\TitleReadInterface;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class ContentWithExtractors extends ContentBase implements
    TitleReadInterface,
    DescriptionReadInterface,
    OriginalUrlReadInterface,
    KeywordsReadInterface
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

    /**
     * Provide a list of the content's keywords for this page to be
     * used in SEO context.
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return 'test, key';
    }
}
