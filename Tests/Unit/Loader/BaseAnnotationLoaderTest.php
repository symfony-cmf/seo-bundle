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

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Cmf\Bundle\SeoBundle\Loader\AnnotationLoader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
abstract class BaseAnnotationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AnnotationLoader
     */
    private $loader;

    protected function setUp()
    {
        $this->loader = new AnnotationLoader(new AnnotationReader());
    }

    abstract protected function getContent($title = 'Default name.', $description = 'Default description.', $keywords = ['keyword1', 'keyword2']);

    public function testPropertyAnnotations()
    {
        $seoMetadata = $this->loader->load($this->getContent());

        $this->assertEquals('Default description.', $seoMetadata->getMetaDescription());
        $this->assertEquals('Default name.', $seoMetadata->getTitle());
        $this->assertEquals('keyword1, keyword2', $seoMetadata->getMetaKeywords());
        $this->assertEquals('/home', $seoMetadata->getOriginalUrl());
        $this->assertEquals(['og:title' => 'Extra Title.'], $seoMetadata->getExtraProperties());
    }

    /**
     * @dataProvider getKeywordAnnotationData
     */
    public function testKeywordAnnotation($contentValue, $seoMetadataValue)
    {
        $seoMetadata = $this->loader->load($this->getContent('', '', $contentValue));

        $this->assertEquals($seoMetadataValue, $seoMetadata->getMetaKeywords());
    }

    public function getKeywordAnnotationData()
    {
        return [
            ['keyword A; keyword B', 'keyword A; keyword B'],
            [new \ArrayIterator(['1st keyword', '2nd keyword']), '1st keyword, 2nd keyword'],
        ];
    }

    /**
     * @dataProvider getDescriptionAnnotationData
     */
    public function testDescriptionAnnotation($contentValue, $seoMetadataValue)
    {
        $seoMetadata = $this->loader->load($this->getContent('', $contentValue));

        $this->assertEquals($seoMetadataValue, $seoMetadata->getMetaDescription());
    }

    public function getDescriptionAnnotationData()
    {
        return [
            ['A much longer default description', 'A much longer default descript...'],
        ];
    }

    public function testExtrasAnnotationWithArray()
    {
        $content = $this->getContent();
        $content->extras = [
            'http-equiv' => ['robots' => 'index, follow'],
        ];

        $seoMetadata = $this->loader->load($content);

        $this->assertEquals(['robots' => 'index, follow'], $seoMetadata->getExtraHttp());
    }

    /**
     * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage Either set the "type" and "key" options for the @Extras annotation or provide an array with extras.
     */
    public function testExtrasAnnotationWithScalar()
    {
        $content = $this->getContent();
        $content->extras = 'index, follow';

        $this->loader->load($content);
    }
}
