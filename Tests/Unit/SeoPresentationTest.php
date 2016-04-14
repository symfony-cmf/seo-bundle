<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit;

use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\ConfigValues;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;

/**
 * This test will cover the behavior of the SeoPresentation Model
 * This model is responsible for putting the SeoMetadata into
 * sonatas PageService.
 */
class SeoPresentationTest extends \PHPUnit_Framework_Testcase
{
    private $seoPresentation;
    private $pageService;
    private $seoMetadata;
    private $translator;
    private $content;
    private $configValues;

    public function setUp()
    {
        $this->pageService = $this->getMock('Sonata\SeoBundle\Seo\SeoPage');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->configValues = new ConfigValues();
        $this->configValues->setDescription('default_description');
        $this->configValues->setTitle('default_title');
        $this->configValues->setOriginalUrlBehaviour(SeoPresentation::ORIGINAL_URL_CANONICAL);

        $this->seoPresentation = new SeoPresentation(
            $this->pageService,
            $this->translator,
            $this->configValues
        );

        $this->seoMetadata = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata');

        $this->content = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent');
        $this->content
            ->expects($this->any())
            ->method('getSeoMetadata')
            ->will($this->returnValue($this->seoMetadata))
        ;
    }

    public function testDefaultTitle()
    {
        // promises
        $this->seoMetadata
            ->expects($this->any())
            ->method('getTitle')
            ->will($this->returnValue('Title test'))
        ;
        $this->translator
            ->expects($this->any())
            ->method('trans')
            ->with('default_title')
            ->will($this->returnValue('Title test | Default Title'))
        ;

        // predictions
        $this->pageService
            ->expects($this->once())
            ->method('setTitle')
            ->with('Title test | Default Title')
        ;

        // test
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testContentTitle()
    {
        // promises
        $this->seoMetadata
            ->expects($this->any())
            ->method('getTitle')
            ->will($this->returnValue('Content title'))
        ;
        $this->configValues->setTitle(null);

        // predictions
        $this->pageService
            ->expects($this->once())
            ->method('setTitle')
            ->with('Content title')
        ;

        // test
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testDefaultDescription()
    {
        // promises
        $this->seoMetadata
            ->expects($this->any())
            ->method('getMetaDescription')
            ->will($this->returnValue('Test description.'))
        ;
        $this->translator
            ->expects($this->any())
            ->method('trans')
            ->with('default_description')
            ->will($this->returnValue('Default Description. Test description.'))
        ;

        // predictions
        $this->pageService
            ->expects($this->once())
            ->method('addMeta')
            ->with('name', 'description', 'Default Description. Test description.')
        ;

        // test
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testContentDescription()
    {
        // promises
        $this->seoMetadata
            ->expects($this->any())
            ->method('getMetaDescription')
            ->will($this->returnValue('Content description.'))
        ;
        $this->configValues->setDescription(null);

        // predictions
        $this->pageService
            ->expects($this->once())
            ->method('addMeta')
            ->with('name', 'description', 'Content description.')
        ;

        // test
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testSettingKeywordsToSeoPage()
    {
        // promises
        $this->seoMetadata
            ->expects($this->any())
            ->method('getMetaKeywords')
            ->will($this->returnValue('key1, key2'))
        ;
        $this->pageService
            ->expects($this->any())
            ->method('getMetas')
            ->will($this->returnValue(array(
                'name' => array(
                    'keywords' => array('default, other', array()),
                ),
            )))
        ;

        // predictions
        $this->pageService
            ->expects($this->once())
            ->method('addMeta')
            ->with('name', 'keywords', 'default, other, key1, key2')
        ;

        // test
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testExtractors()
    {
        // promises
        $this->translator
            ->expects($this->any())
            ->method('trans')
            ->will($this->returnValue('translation strategy test'))
        ;
        $extractor = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface');
        $extractor
            ->expects($this->any())
            ->method('supports')
            ->with($this->content)
            ->will($this->returnValue(true))
        ;
        $this->seoPresentation->addExtractor($extractor);

        // predictions
        $extractor
            ->expects($this->once())
            ->method('updateMetadata')
        ;

        // test
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testTitleExtractorsWithPriority()
    {
        // promises
        $this->translator
            ->expects($this->any())
            ->method('trans')
            ->with($this->equalTo('default_title'), $this->equalTo(array('%content_title%' => 'Final Title')), $this->equalTo(null))
            ->will($this->returnValue('translation strategy test'))
        ;
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
        $this->seoPresentation->addExtractor($extractorDefault);
        $this->seoPresentation->addExtractor($extractorOne, 1);

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
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testDescriptionExtractorsWithPriority()
    {
        $this->translator
            ->expects($this->any())
            ->method('trans')
            ->with($this->equalTo('default_description'), $this->equalTo(array('%content_description%' => 'Final Description')), $this->equalTo(null))
            ->will($this->returnValue('translation strategy test'))
        ;

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
        $this->seoPresentation->addExtractor($extractorDefault);
        $this->seoPresentation->addExtractor($extractorOne, 1);

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
        $this->pageService
            ->expects($this->once())
            ->method('addMeta')
            ->with('name', 'description', 'translation strategy test')
        ;

        // test
        $this->seoPresentation->updateSeoPage($this->content);
    }

    public function testRedirect()
    {
        // promises
        $this->seoMetadata
            ->expects($this->any())
            ->method('getOriginalUrl')
            ->will($this->returnValue('/redirect/target'))
        ;
        $this->configValues->setOriginalUrlBehaviour(SeoPresentation::ORIGINAL_URL_REDIRECT);

        // test
        $this->seoPresentation->updateSeoPage($this->content);

        // assertions
        $redirect = $this->seoPresentation->getRedirectResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $redirect);
        $this->assertEquals('/redirect/target', $redirect->getTargetUrl());
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
        $seoPresentation = new SeoPresentation(
            $this->pageService,
            $this->translator,
            $this->configValues,
            $cache
        );

        // predictions
        $cache
            ->expects($this->once())
            ->method('putExtractorsInCache')
        ;

        $seoPresentation->updateSeoPage($this->content);
        $seoPresentation->updateSeoPage($this->content);

        return array($seoPresentation, $cache, $extractors);
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
        $seoPresentation = new SeoPresentation(
            $this->pageService,
            $this->translator,
            $this->configValues,
            $cache
        );

        // predictions
        $cache
            ->expects($this->once())
            ->method('putExtractorsInCache')
        ;

        $seoPresentation->updateSeoPage($this->content);

        return array($seoPresentation, $cache, $extractors);
    }

    public function testSeoAwareWithoutCurrentMetadata()
    {
        $content = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent');
        $content
            ->expects($this->any())
            ->method('getSeoMetadata')
            ->will($this->returnValue(null))
        ;

        $content
            ->expects($this->once())
            ->method('setSeoMetadata')
            ->with($this->callback(function ($c) { return $c instanceof SeoMetadataInterface; }))
        ;

        $this->seoPresentation->updateSeoPage($content);
    }
}
