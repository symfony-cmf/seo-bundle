<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentation;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

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

    public function setUp()
    {
        $this->pageService = new SeoPage();
        $this->SUT = new SeoPresentation($this->pageService);

        $this->seoMetadata = new SeoMetadata();

        $this->SUT->setSeoMetadata($this->seoMetadata);
    }

    /**
     * @dataProvider provideSeoMetadataValues
     */
    public function testSettingTitleFromSeoMetadataToPageService($titleParameters, $expectedValue)
    {
        //values for every SeoMetadata
        $this->seoMetadata->setTitle('Special title');

        //setting the values for the title parameters
        $this->SUT->setTitleParameters($titleParameters);

        //run the transformation
        $this->SUT->setMetaDataValues();

        //do the asserts
        $this->assertEquals($expectedValue, $this->pageService->getTitle());
    }


    /**
     * Data provider for different title settings
     * @return array
     */
    public function provideSeoMetadataValues()
    {
        return array(
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   =>  'Default title'
                ),
                'Special title | Default title'
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'append',
                    'default'   =>  'Default title'
                ),
                'Default title | Special title'
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'replace',
                    'default'   =>  'Default title'
                ),
                'Special title'
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   =>  ''
                ),
                'Special title'
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   => ''
                ),
                'Special title'
            )
        );
    }
}
