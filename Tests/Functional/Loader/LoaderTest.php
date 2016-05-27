<?php

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
