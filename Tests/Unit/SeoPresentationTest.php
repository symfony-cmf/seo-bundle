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
        /*
        $this->container = $this->getMockBuilder('Symfony\Component\DependencyInjection\Container')
                                ->disableOriginalConstructor()
                                ->setMethods(array('set', 'get'))
                                ->getMock();
        */
        $this->SUT->setContainer($this->container);
    }

    /**
     * @dataProvider provideSeoMetadataValues
     */
    public function testSettingSeoMetadataToPageService($seoConfig, $expectedValues)
    {
        //values for every SeoMetadata
        $this->seoMetadata->setTitle('Special title');
        $this->seoMetadata->setMetaDescription('Special description');
        $this->seoMetadata->setMetaKeywords('special');

        //default configs like in the sonata_seo config block
        $this->pageService->setTitle('Default title');
        $this->pageService->setMetas(
            array(
                'names'  => array(
                    'description' => 'default description',
                    'keywords'    => 'keys, default'
                )
            )
        );
        print("container: ". get_class($this->container));
        //setting the config to the container mock
        foreach ($seoConfig as $key => $value) {
            $this->container->set($key, $value);
        }

        //do the asserts
        foreach ($expectedValues as $key => $value) {
            if ($key != 'title') {
                $this->assertEquals($value, $this->pageService->getMetas()['names'][$key]);
            } else {
                $this->assertEquals($value, $this->pageService->getTitle());
            }
        }
    }


    public function provideSeoMetadataValues()
    {
        return array(
          array(
              array(
                  'cmf_seo.title.strategy'  => 'prepend',
                  'cmf_seo.title'           => ' | '
              ),
              array(
                  'title' => 'Special title | Default title',
                  'description' => 'default description. Special description',
                  'keywords'    => 'keys, default, special'
              )
          )
        );
    }
}
