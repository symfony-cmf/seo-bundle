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
     * @param $titleParameters
     * @param $expectedValue
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

    public function provideSeoMetadataValues()
    {
        return array(
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   =>  'Default title',
                ),
                'Special title | Default title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'append',
                    'default'   =>  'Default title',
                ),
                'Default title | Special title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'replace',
                    'default'   =>  'Default title',
                ),
                'Special title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   =>  '',
                ),
                'Special title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   => '',
                ),
                'Special title',
            )
        );
    }

    public function testSettingDescriptionToSeoPage()
    {
        $this->seoMetadata->setMetaDescription('Special description');
        //to set it here is the same as it was set in the sonata_seo settings
        $this->pageService->addMeta('names', 'description', 'Default description');
        $this->SUT->setMetaDataValues();
        $this->assertEquals(
            'Default description. Special description',
            $this->pageService->getMetas()['names']['description'][0]
        );
    }

    public function testSettingKeywordsToSeoPage()
    {
        $this->seoMetadata->setMetaKeywords('key1, key2');
        //to set it here is the same as it was set in the sonata_seo settings
        $this->pageService->addMeta('names', 'keywords', 'default, other');
        $this->SUT->setMetaDataValues();
        $this->assertEquals(
            'default, other, key1, key2',
            $this->pageService->getMetas()['names']['keywords'][0]
        );
    }

    /**
     * @param $titleParameters
     * @param $locale
     * @param $expectedValue
     *
     * @dataProvider provideMultilangTitleParameters
     */
    public function testSettingMultilangTitleToSeoPage($titleParameters, $locale, $expectedValue)
    {
        $this->seoMetadata->setTitle('Special title');

        $this->SUT->setLocale($locale);
        $this->SUT->setTitleParameters($titleParameters);

        $this->SUT->setMetaDataValues();

        $this->assertEquals($expectedValue, $this->pageService->getTitle());
    }

    public function provideMultilangTitleParameters()
    {
        return array(
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   =>  array(
                        'en' => 'Default title',
                        'fr' => 'title de default',
                        'de' => 'Der Title',
                    )
                ),
                'en',
                'Special title | Default title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   =>  array(
                        'en' => 'Default title',
                        'fr' => 'title de default',
                        'de' => 'Der Title',
                    )
                ),
                'fr',
                'Special title | title de default',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'strategy'  => 'prepend',
                    'default'   =>  array(
                        'en' => 'Default title',
                        'fr' => 'title de default',
                        'de' => 'Der Titel',
                    )
                ),
                'de',
                'Special title | Der Titel',
            )
        );
    }
}
