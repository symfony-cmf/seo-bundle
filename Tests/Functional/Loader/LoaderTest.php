<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Loader;

use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\ContentWithExtractors;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\MethodAnnotatedContent;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class LoaderTest extends BaseTestCase
{
    /**
     * @dataProvider getLoadContentData
     */
    public function testLoadContent($content)
    {
        $seoMetadata = $this->getContainer()->get('cmf_seo.loader')->load($content);

        $this->assertEquals('Default name.', $seoMetadata->getTitle());
    }

    public function getLoadContentData()
    {
        return [
            [new MethodAnnotatedContent()],
            [(new ContentWithExtractors())->setTitle('Default name.')],
        ];
    }
}
