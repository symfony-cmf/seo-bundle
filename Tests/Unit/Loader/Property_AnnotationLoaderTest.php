<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Loader;

use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\PropertyAnnotatedContent;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class Property_AnnotationLoaderTest extends BaseAnnotationLoaderTest
{
    protected function getContent($title = 'Default name.', $description = 'Default description.', $keywords = ['keyword1', 'keyword2'])
    {
        return new PropertyAnnotatedContent($title, $description, $keywords);
    }
}
