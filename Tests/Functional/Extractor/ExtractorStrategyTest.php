<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\TitleReadExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\ReadTitleExtractorDocument;

/**
 * This test covers the behavior of all provided strategies.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ExtractorStrategyTest extends \PHPUnit_Framework_TestCase
{
    private $titleDocument;
    private $descriptionDocument;
    private $routeDocument;
    private $urlDocument;

    /** @var  SeoMetadata */
    private $seoMetadata;

    private $urlGenerator;

    public function setUp()
    {
        $this->urlGenerator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');

        $this->titleDocument = $this->getMock(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\TitleExtractorDocument'
        );
        $this->descriptionDocument = $this->getMock(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\DescriptionExtractorDocument'
        );
        $this->urlDocument = $this->getMock(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\UrlExtractorDocument'
        );
        $this->routeDocument = $this->getMock(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\RouteExtractorDocument'
        );

        $this->seoMetadata = new SeoMetadata();
    }

    public function tearDown()
    {
        unset($this->seoMetadata);
    }

    public function testTitleExtractorStrategy()
    {
        $strategy = new SeoTitleExtractor();

        $this->assertFalse($strategy->supports($this->descriptionDocument));
        $this->assertFalse($strategy->supports($this->routeDocument));
        $this->assertTrue($strategy->supports($this->titleDocument));

        $this->titleDocument->expects($this->once())
                            ->method('getSeoTitle')
                            ->will($this->returnValue('seo-title'));

        $strategy->updateMetadata($this->titleDocument, $this->seoMetadata);

        $this->assertEquals('seo-title', $this->seoMetadata->getTitle());
    }

    public function testDescriptionExtractor()
    {
        $strategy = new SeoDescriptionExtractor();

        $this->assertTrue($strategy->supports($this->descriptionDocument));
        $this->assertFalse($strategy->supports($this->routeDocument));
        $this->assertFalse($strategy->supports($this->titleDocument));
        $this->assertFalse($strategy->supports($this->urlDocument));

        $this->descriptionDocument->expects($this->once())
            ->method('getSeoDescription')
            ->will($this->returnValue('seo-description'));

        $strategy->updateMetadata($this->descriptionDocument, $this->seoMetadata);

        $this->assertEquals('seo-description', $this->seoMetadata->getMetaDescription());
    }

    public function testRouteExtractor()
    {
        $strategy = new SeoOriginalRouteExtractor();

        $this->assertFalse($strategy->supports($this->descriptionDocument));
        $this->assertTrue($strategy->supports($this->routeDocument));
        $this->assertFalse($strategy->supports($this->titleDocument));
        $this->assertFalse($strategy->supports($this->urlDocument));

        $route = $this->getMockBuilder('Symfony\Component\Routing\Route')
                      ->disableOriginalConstructor()
                      ->getMock();

        $this->routeDocument->expects($this->once())
                            ->method('getSeoOriginalRoute')
                            ->will($this->returnValue($route));

        $this->urlGenerator->expects($this->once())
                     ->method('generate')
                     ->with($route)
                     ->will($this->returnValue('/seo-route'));
        $strategy->setRouter($this->urlGenerator);

        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);

        $this->assertEquals('/seo-route', $this->seoMetadata->getOriginalUrl());
    }

    /**
     * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException
     */
    public function testRouteExtractorExceptions()
    {
        //should throw cause not supported
        $strategy = new SeoOriginalRouteExtractor();
        $strategy->updateMetadata($this->urlDocument, $this->seoMetadata);

        //throws cause a route object expected
        $strategy = new SeoOriginalRouteExtractor();
        $this->routeDocument->expects($this->once())
                            ->method('getSeoOriginalRoute')
                            ->will($this->returnValue('no route'));
        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);
    }

    public function testUrlExtractor()
    {
        $strategy = new SeoOriginalUrlExtractor();

        $this->assertFalse($strategy->supports($this->descriptionDocument));
        $this->assertTrue($strategy->supports($this->urlDocument));
        $this->assertFalse($strategy->supports($this->titleDocument));
        $this->assertFalse($strategy->supports($this->routeDocument));

        $this->urlDocument->expects($this->once())
                          ->method('getSeoOriginalUrl')
                          ->will($this->returnValue('/seo-route'));

        $strategy->updateMetadata($this->urlDocument, $this->seoMetadata);

        $this->assertEquals('/seo-route', $this->seoMetadata->getOriginalUrl());
    }

    /**
    * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException
    */
    public function testExceptionWhenServingWrongDocument()
    {
        $strategy = new SeoOriginalRouteExtractor();
        $strategy->updateMetadata($this->descriptionDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->titleDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->urlDocument, $this->seoMetadata);

        $strategy = new SeoTitleExtractor();
        $strategy->updateMetadata($this->descriptionDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->urlDocument, $this->seoMetadata);

        $strategy = new SeoDescriptionExtractor();
        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->titleDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->urlDocument, $this->seoMetadata);

        $strategy = new SeoOriginalUrlExtractor();
        $strategy->updateMetadata($this->descriptionDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->titleDocument, $this->seoMetadata);
    }

    public function testReadTitleExtractor()
    {
        $strategy = new TitleReadExtractor();

        $document = new ReadTitleExtractorDocument();
        $this->assertFalse($strategy->supports($this->descriptionDocument));
        $this->assertFalse($strategy->supports($this->urlDocument));
        $this->assertFalse($strategy->supports($this->titleDocument));
        $this->assertFalse($strategy->supports($this->routeDocument));
        $this->assertTrue($strategy->supports($document));

        $strategy->updateMetadata($document, $this->seoMetadata);

        $this->assertEquals('title-test', $this->seoMetadata->getTitle());
    }
}
