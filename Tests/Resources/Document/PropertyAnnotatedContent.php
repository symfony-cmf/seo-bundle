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

use Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation as SEO;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class PropertyAnnotatedContent
{
    /**
     * @SEO\Title
     */
    private $name;

    /**
     * @SEO\MetaDescription(truncate=30)
     */
    private $description;

    /**
     * @SEO\MetaKeywords
     */
    private $keywords;

    /**
     * @SEO\OriginalUrl
     */
    private $originalUrl = '/home';

    /**
     * @SEO\Extras(type="property", key="og:title")
     */
    protected $ogTitle = 'Extra Title.';

    /**
     * @SEO\Extras
     */
    public $extras = [];

    public function __construct($name = 'Default name.', $description = 'Default description.', $keywords = ['keyword1', 'keyword2'])
    {
        $this->name = $name;
        $this->description = $description;
        $this->keywords = $keywords;
    }
}
