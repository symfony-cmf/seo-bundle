<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentation;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * This test will cover the behavior of the SeoPresentation Model
 * This model is responsible for putting the SeoMetadata into
 * sonatas PageService.
 *
 * Class SeoPresentationTest
 * @package Symfony\Cmf\Bundle\SeoBundle\Tests\Unit
 */
class SeoPresentationTest extends BaseTestCase
{
    /**
     * @var SeoPresentation
     */
    private $SUT;

    /**
     * @var SeoPage
     */
    private $pageService;

    /**
     * @var SeoMetadata
     */
    private $seoMetadata;

    private $containerMock;

    public function setUp()
    {
        $this->pageService = new SeoPage();
        $this->SUT = new SeoPresentation($this->pageService);

        $this->seoMetadata = new SeoMetadata();

        $this->SUT->setSeoMetadata($this->seoMetadata);

        $this->containerMock = $this->getMock('Symfony\Component\DependencyInjection\Container', array('getParameter'));

        $this->SUT->setContainer($this->containerMock);
    }

    /**
     * @dataProvider provideSeoMetadataValues
     */
    public function testSettingTitleFromSeoMetadataToPageService($titleSeparator, $titleStrategy, $expectedValue)
    {
        //values for every SeoMetadata
        $this->seoMetadata->setTitle('Special title');

        //default configs like in the sonata_seo config block
        $this->pageService->setTitle('Default title');

        //setting the config to the container mock
        $this->containerMock->expects($this->at(1))
                            ->method('getParameter')
                            ->with($this->equalTo('cmf_seo.title.separator'))
                            ->will($this->returnValue($titleSeparator));

        $this->containerMock->expects($this->at(2))
                            ->method('getParameter')
                            ->with($this->equalTo('cmf_seo.title.strategy'))
                            ->will($this->returnValue($titleStrategy));

        $this->SUT->setMetaDataValues();

        //do the asserts
        $this->assertEquals($expectedValue, $this->pageService->getTitle());
    }


    public function provideSeoMetadataValues()
    {
        return array(
            array(' | ', 'prepend', 'Special title | Default title'),
            array(' | ', 'append', 'Default title | Special title'),
            array(' | ', 'replace', 'Special title')
        );
    }
}
