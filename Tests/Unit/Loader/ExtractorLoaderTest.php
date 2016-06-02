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

use Symfony\Cmf\Bundle\SeoBundle\Loader\ExtractorLoader;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class ExtractorLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtractorLoader
     */
    private $loader;
    private $content;

    protected function setUp()
    {
        $this->loader = new ExtractorLoader();
        $this->content = new \stdClass();
    }

    public function testExtractors()
    {
        // promises
        $extractor = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface');
        $extractor
            ->expects($this->any())
            ->method('supports')
            ->with($this->content)
            ->will($this->returnValue(true))
        ;
        $this->loader->addExtractor($extractor);

        // predictions
        $extractor
            ->expects($this->once())
            ->method('updateMetadata')
        ;

        // test
        $this->loader->load($this->content);
    }

    public function testTitleExtractorsWithPriority()
    {
        // promises
        $extractorDefault = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface');
        $extractorDefault
            ->expects($this->any())
            ->method('supports')
            ->with($this->content)
            ->will($this->returnValue(true))
        ;
        $extractorOne = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface');
        $extractorOne
            ->expects($this->any())
            ->method('supports')
            ->with($this->content)
            ->will($this->returnValue(true))
        ;
        $this->loader->addExtractor($extractorDefault);
        $this->loader->addExtractor($extractorOne, 1);

        // predictions
        $extractorDefault
            ->expects($this->once())
            ->method('updateMetadata')
            ->will($this->returnCallback(function ($content, SeoMetadataInterface $seoMetadata) {
                $seoMetadata->setTitle('First Title');
            }))
        ;
        $extractorOne
            ->expects($this->once())
            ->method('updateMetadata')
            ->will($this->returnCallback(function ($content, SeoMetadataInterface $seoMetadata) {
                $seoMetadata->setTitle('Final Title');
            }))
        ;

        // test
        $seoMetadata = $this->loader->load($this->content);
        $this->assertEquals('Final Title', $seoMetadata->getTitle());
    }

    public function testDescriptionExtractorsWithPriority()
    {
        // promises
        $extractorDefault = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface');
        $extractorDefault
            ->expects($this->any())
            ->method('supports')
            ->with($this->content)
            ->will($this->returnValue(true))
        ;
        $extractorOne = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface');
        $extractorOne
            ->expects($this->any())
            ->method('supports')
            ->with($this->content)
            ->will($this->returnValue(true))
        ;
        $this->loader->addExtractor($extractorDefault);
        $this->loader->addExtractor($extractorOne, 1);

        // predictions
        $extractorDefault
            ->expects($this->once())
            ->method('updateMetadata')
            ->will($this->returnCallback(function ($content, SeoMetadataInterface $seoMetadata) {
                $seoMetadata->setMetaDescription('First Description');
            }))
        ;
        $extractorOne
            ->expects($this->once())
            ->method('updateMetadata')
            ->will($this->returnCallback(function ($content, SeoMetadataInterface $seoMetadata) {
                $seoMetadata->setMetaDescription('Final Description');
            }))
        ;

        // test
        $seoMetadata = $this->loader->load($this->content);
        $this->assertEquals('Final Description', $seoMetadata->getMetaDescription());
    }

    public function testCaching()
    {
        // promises
        $extractors = $this->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\Cache\CachedCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $extractors
            ->expects($this->any())
            ->method('isFresh')
            ->will($this->returnValue(true))
        ;
        $extractors
            ->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator()))
        ;
        $cacheItemNoHit = $this->getMock('Psr\Cache\CacheItemInterface');
        $cacheItemNoHit->expects($this->any())->method('isHit')->will($this->returnValue(false));
        $cacheItemNoHit->expects($this->any())->method('get')->will($this->returnValue($extractors));
        $cacheItemHit = $this->getMock('Psr\Cache\CacheItemInterface');
        $cacheItemHit->expects($this->any())->method('isHit')->will($this->returnValue(true));
        $cacheItemHit->expects($this->any())->method('get')->will($this->returnValue($extractors));
        $cache = $this->getMock('Psr\Cache\CacheItemPoolInterface');
        $cache
            ->expects($this->any())
            ->method('getItem')
            ->will($this->onConsecutiveCalls($cacheItemNoHit, $cacheItemHit))
        ;
        $loader = new ExtractorLoader($cache);

        // predictions
        $cache
            ->expects($this->once())
            ->method('save')
        ;

        $loader->load($this->content);
        $loader->load($this->content);
    }

    public function testCacheRefresh()
    {
        // promises
        $extractors = $this->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\Cache\CachedCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $extractors
            ->expects($this->any())
            ->method('isFresh')
            ->will($this->returnValue(false))
        ;
        $extractors
            ->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator()))
        ;
        $cacheItem = $this->getMock('Psr\Cache\CacheItemInterface');
        $cacheItem->expects($this->any())->method('isHit')->will($this->returnValue(true));
        $cacheItem->expects($this->any())->method('get')->will($this->returnValue($extractors));
        $cache = $this->getMock('Psr\Cache\CacheItemPoolInterface');
        $cache
            ->expects($this->any())
            ->method('getItem')
            ->will($this->returnValue($cacheItem))
        ;
        $loader = new ExtractorLoader($cache);

        // predictions
        $cache
            ->expects($this->once())
            ->method('save')
        ;

        $loader->load($this->content);
    }
}
