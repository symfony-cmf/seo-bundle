<?php

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
        $this->content = new \stdClass;
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
        $extractors = $this->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\Cache\ExtractorCollection')
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
        $cache = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Cache\CacheInterface');
        $cache
            ->expects($this->any())
            ->method('loadExtractorsFromCache')
            ->will($this->onConsecutiveCalls(null, $extractors))
        ;
        $loader = new ExtractorLoader($cache);

        // predictions
        $cache
            ->expects($this->once())
            ->method('putExtractorsInCache')
        ;

        $loader->load($this->content);
        $loader->load($this->content);
    }

    public function testCacheRefresh()
    {
        // promises
        $extractors = $this->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\Cache\ExtractorCollection')
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
        $cache = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Cache\CacheInterface');
        $cache
            ->expects($this->any())
            ->method('loadExtractorsFromCache')
            ->will($this->returnValue($extractors))
        ;
        $loader = new ExtractorLoader($cache);

        // predictions
        $cache
            ->expects($this->once())
            ->method('putExtractorsInCache')
        ;

        $loader->load($this->content);
    }
}
