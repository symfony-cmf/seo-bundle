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

use Symfony\Cmf\Bundle\SeoBundle\Cache\CachedCollection;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface;
use Symfony\Cmf\Bundle\SeoBundle\Loader\ExtractorLoader;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Prophecy\Argument;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

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
        $extractor = $this->prophesize(ExtractorInterface::class);
        $extractor->supports($this->content)->willReturn(true);

        $this->loader->addExtractor($extractor->reveal());

        // predictions
        $extractor->updateMetadata($this->content, Argument::type(SeoMetadataInterface::class))->shouldBeCalled();

        // test
        $this->loader->load($this->content);
    }

    public function testExtractorsWithPriority()
    {
        // promises
        $extractorDefault = $this->prophesize(ExtractorInterface::class);
        $extractorDefault->supports($this->content)->willReturn(true);

        $extractorOne = $this->prophesize(ExtractorInterface::class);
        $extractorOne->supports($this->content)->willReturn(true);

        $this->loader->addExtractor($extractorDefault->reveal());
        $this->loader->addExtractor($extractorOne->reveal(), 1);

        // predictions
        $extractorDefault->updateMetadata(Argument::cetera())->will(function ($arguments) {
            $arguments[1]->setTitle('First Title');
        });
        $extractorOne->updateMetadata(Argument::cetera())->will(function ($arguments) {
            $arguments[1]->setTitle('Final Title');
        });

        // test
        $seoMetadata = $this->loader->load($this->content);
        $this->assertEquals('Final Title', $seoMetadata->getTitle());
    }

    public function testCaching()
    {
        // promises
        $extractors = $this->prophesize(CachedCollection::class);
        $extractors->isFresh()->willReturn(true);
        $extractors->getIterator()->willReturn(new \ArrayIterator());

        $cacheItemProphet = $this->prophesize(CacheItemInterface::class);
        $cacheItemProphet->isHit()->willReturn(false);
        $cacheItemProphet->set(Argument::type(CachedCollection::class))->will(function () {
            $this->isHit()->willReturn(true);
        });
        $cacheItemProphet->get()->willReturn($extractors->reveal());
        $cacheItem = $cacheItemProphet->reveal();

        $cache = $this->prophesize(CacheItemPoolInterface::class);
        $cache->getItem('cmf_seo.extractors.stdClass')->willReturn($cacheItem);

        $loader = new ExtractorLoader($cache->reveal());

        // predictions
        $cache->save($cacheItem)->shouldBeCalledTimes(1);

        $loader->load($this->content);
        $loader->load($this->content);
    }

    public function testCacheRefresh()
    {
        // promises
        $extractors = $this->prophesize(CachedCollection::class);
        $extractors->isFresh()->willReturn(false);
        $extractors->getIterator()->willReturn(new \ArrayIterator());

        $cacheItemProphet = $this->prophesize(CacheItemInterface::class);
        $cacheItemProphet->isHit()->willReturn(true);
        $cacheItemProphet->get()->willReturn($extractors->reveal());
        $cacheItem = $cacheItemProphet->reveal();

        $cache = $this->prophesize(CacheItemPoolInterface::class);
        $cache->getItem('cmf_seo.extractors.stdClass')->willReturn($cacheItem);

        $loader = new ExtractorLoader($cache->reveal());

        // predictions
        $cacheItemProphet->set(Argument::type(CachedCollection::class))->shouldBeCalled();
        $cache->save($cacheItem)->shouldBeCalled();

        $loader->load($this->content);
    }
}
