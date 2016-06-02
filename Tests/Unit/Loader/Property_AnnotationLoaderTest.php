<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
