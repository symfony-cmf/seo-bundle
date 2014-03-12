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

    private $dmMock;

    private $unitOfWork;

    private $document;

    public function setUp()
    {
        $this->pageService = new SeoPage();
        $this->SUT = new SeoPresentation($this->pageService);

        $this->seoMetadata = new SeoMetadata();

        //need a mock for the manager registry
        $managerRegistry = $this->getMockBuilder('Doctrine\Bundle\PHPCRBundle\ManagerRegistry')
                                ->disableOriginalConstructor()
                                ->getMock();

        //need the DM and unitOfWork for getting the locale out of the document
        $this->dmMock = $this   ->getMockBuilder('Doctrine\ODM\PHPCR\DocumentManager')
                                ->disableOriginalConstructor()
                                ->getMock();

        $managerRegistry->expects($this->any())
                        ->method('getManager')
                        ->will($this->returnValue($this->dmMock));

        $this->unitOfWork = $this   ->getMockBuilder('Doctrine\ODM\PHPCR\UnitOfWork')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->dmMock->expects($this->any())
                         ->method('getUnitOfWork')
                         ->will($this->returnValue($this->unitOfWork));

        //mock the current document to answer with the seo metadata
        $this->document = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface');
        $this->document->expects($this->once())
                       ->method('getSeoMetadata')
                       ->will($this->returnValue($this->seoMetadata));

        //settings for the presentation model
        $this->SUT->setDoctrineRegistry($managerRegistry);
        $this->SUT->setContentDocument($this->document);
    }

    public function tearDown()
    {
        unset($this->seoMetadata);
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
                    'pattern'  => 'prepend',
                    'default'   =>  'Default title',
                ),
                'Special title | Default title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'pattern'  => 'append',
                    'default'   =>  'Default title',
                ),
                'Default title | Special title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'pattern'  => 'replace',
                    'default'   =>  'Default title',
                ),
                'Special title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'pattern'  => 'prepend',
                    'default'   =>  '',
                ),
                'Special title',
            ),
            array(
                array(
                    'separator' => ' | ',
                    'pattern'  => 'prepend',
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
        $metas = $this->pageService->getMetas();
        $this->assertEquals(
            'Default description. Special description',
            $metas['names']['description'][0]
        );
    }

    public function testSettingKeywordsToSeoPage()
    {
        $this->seoMetadata->setMetaKeywords('key1, key2');
        //to set it here is the same as it was set in the sonata_seo settings
        $this->pageService->addMeta('names', 'keywords', 'default, other');
        $this->SUT->setMetaDataValues();
        $keywords = $this->pageService->getMetas();
        $this->assertEquals(
            'default, other, key1, key2',
            $keywords['names']['keywords'][0]
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

        $this->unitOfWork->expects($this->once())
                         ->method('getCurrentLocale')
                         ->will($this->returnValue($locale));

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
                    'pattern'  => 'prepend',
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
                    'pattern'  => 'prepend',
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
                    'pattern'  => 'prepend',
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

    public function testDefaultLocaleFallbackForDefaultTitleTranslation()
    {
        $this->seoMetadata->setTitle('Special title');

        $titleValues = array(
            'separator' => ' | ',
            'pattern'  => 'prepend',
            'default'   =>  array(
                'en' => 'Default title',
                'fr' => 'title de default',
                'de' => 'Der Title',
            )
        );

        $this->SUT->setTitleParameters($titleValues);
        $this->SUT->setDefaultLocale('en');

        $this->unitOfWork->expects($this->once())->method('getCurrentLocale')->will($this->returnValue('nl'));

        $this->SUT->setMetaDataValues();

        $this->assertEquals('Special title | Default title', $this->pageService->getTitle());
    }

    /**
     * @expectedException Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException
     */
    public function testDefaultLocationFallbackBreakThrowsException()
    {
        $this->seoMetadata->setTitle('Special title');

        $titleValues = array(
            'separator' => ' | ',
            'pattern'  => 'prepend',
            'default'   =>  array(
                'en' => 'Default title',
                'fr' => 'title de default',
                'de' => 'Der Title',
            )
        );

        $this->SUT->setTitleParameters($titleValues);
        $this->SUT->setDefaultLocale('nl');

        $this->unitOfWork->expects($this->once())->method('getCurrentLocale')->will($this->returnValue('nl'));

        $this->SUT->setMetaDataValues();
    }
}
