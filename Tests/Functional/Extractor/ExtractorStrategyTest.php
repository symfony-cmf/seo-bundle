<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionExtractorStrategy;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoMetadataExtractorStrategy;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteExtractorStrategy;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleExtractorStrategy;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;

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

    /** @var  SeoMetadata */
    private $seoMetadata;

    public function setUp()
    {
        $this->titleDocument = $this->getMock(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\TitleExtractorDocument'
        );
        $this->descriptionDocument = $this->getMock(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\DescriptionExtractorDocument'
        );
        $this->routeDocument = $this->getMock(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures\RouteExtractorDocument'
        );
        $this->seoMetadata = new SeoMetadata();
    }

    public function testTitleExtractorStrategy()
    {
        $strategy = new SeoTitleExtractorStrategy();

        $this->assertFalse($strategy->supports($this->descriptionDocument));
        $this->assertFalse($strategy->supports($this->routeDocument));
        $this->assertTrue($strategy->supports($this->titleDocument));

        $this->titleDocument->expects($this->once())
                            ->method('extractTitle')
                            ->will($this->returnValue('seo-title'));

        $strategy->updateMetadata($this->titleDocument, $this->seoMetadata);

        $this->assertEquals('seo-title', $this->seoMetadata->getTitle());
    }

    public function testDescriptionExtractorStrategy()
    {
        $strategy = new SeoDescriptionExtractorStrategy();

        $this->assertTrue($strategy->supports($this->descriptionDocument));
        $this->assertFalse($strategy->supports($this->routeDocument));
        $this->assertFalse($strategy->supports($this->titleDocument));

        $this->descriptionDocument->expects($this->once())
            ->method('extractDescription')
            ->will($this->returnValue('seo-description'));

        $strategy->updateMetadata($this->descriptionDocument, $this->seoMetadata);

        $this->assertEquals('seo-description', $this->seoMetadata->getMetaDescription());
    }

    public function testRotueExtractorStrategy()
    {
        $strategy = new SeoOriginalRouteExtractorStrategy();

        $this->assertFalse($strategy->supports($this->descriptionDocument));
        $this->assertTrue($strategy->supports($this->routeDocument));
        $this->assertFalse($strategy->supports($this->titleDocument));

        $this->routeDocument->expects($this->once())
            ->method('extractOriginalRoute')
            ->will($this->returnValue('seo-route'));

        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);

        $this->assertEquals('seo-route', $this->seoMetadata->getOriginalUrl());
    }


    public function testMetadataExtractorStrategy()
    {
        $strategy = new SeoMetadataExtractorStrategy();

        $seoMetadata = new SeoMetadata();
        $seoMetadata->setOriginalUrl('seo-route');
        $seoMetadata->setTitle('seo-title');
        $seoMetadata->setMetaKeywords('keys');
        $seoMetadata->setMetaDescription('seo-description');

        $this->routeDocument->expects($this->any())
                            ->method('getSeoMetadata')
                            ->will($this->returnValue($seoMetadata));

        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);

        $this->assertEquals($seoMetadata, $this->seoMetadata);
    }

    /**
     * @expectedException Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException
     */
    public function testExceptionWhenServingWrongDocument()
    {
        $strategy = new SeoOriginalRouteExtractorStrategy();
        $strategy->updateMetadata($this->descriptionDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->titleDocument, $this->seoMetadata);

        $strategy = new SeoTitleExtractorStrategy();
        $strategy->updateMetadata($this->descriptionDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);

        $strategy = new SeoDescriptionExtractorStrategy();
        $strategy->updateMetadata($this->routeDocument, $this->seoMetadata);
        $strategy->updateMetadata($this->titleDocument, $this->seoMetadata);
    }
}
