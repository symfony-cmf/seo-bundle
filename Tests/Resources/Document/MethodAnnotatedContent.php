<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation as SEO;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class MethodAnnotatedContent
{
    private $name;
    private $description;
    private $keywords;
    public $extras = [];

    public function __construct($name = 'Default name.', $description = 'Default description.', $keywords = ['keyword1', 'keyword2'])
    {
        $this->name = $name;
        $this->description = $description;
        $this->keywords = $keywords;
    }

    /**
     * @SEO\Title
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @SEO\MetaDescription(truncate=30)
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @SEO\MetaKeywords
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @SEO\OriginalUrl
     */
    public function getOriginalUrl()
    {
        return '/home';
    }

    /**
     * @SEO\Extras(type="property", key="og:title")
     */
    public function getOgTitle()
    {
        return 'Extra Title.';
    }

    /**
     * @SEO\Extras
     */
    public function getExtras()
    {
        return $this->extras;
    }
}
