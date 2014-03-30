<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class ContentWithExtractors extends ContentBase implements
    SeoTitleInterface,
    SeoDescriptionInterface,
    SeoOriginalUrlInterface
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
