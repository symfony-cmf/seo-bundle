<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Loader;

use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\MethodAnnotatedContent;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class Method_AnnotationLoaderTest extends BaseAnnotationLoaderTest
{
    protected function getContent($title = 'Default name.', $description = 'Default description.', $keywords = ['keyword1', 'keyword2']) {
        return new MethodAnnotatedContent($title, $description, $keywords);
    }
}
