<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoConfigValues;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\AllStrategiesDocument;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleExtractor;
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
    private $seoPresentation;

    /**
     * @var SeoPage
     */
    private $pageService;

    /**
     * @var SeoMetadata
     */
    private $seoMetadata;
    private $translator;
    private $document;
    private $configValues;

    public function setUp()
    {
        $this->pageService = new SeoPage();
        $this->translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
                                 ->disableOriginalConstructor()
                                 ->getMock();
        $this->configValues = new SeoConfigValues();
        $this->configValues->setDescriptionKey('description_key');
        $this->configValues->setTitleKey('title_key');
        $this->configValues->setOriginalRoutePattern('canonical');
        $this->configValues->setTranslationDomain(null);

        $this->seoPresentation = new SeoPresentation(
            $this->pageService,
            $this->translator,
            $this->configValues
        );

        $this->seoMetadata = new SeoMetadata();

        // create the mock for the document
        $this->document = $this->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent')
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->document->expects($this->any())
                       ->method('getSeoMetadata')
                       ->will($this->returnValue($this->seoMetadata))
            ;
    }

    public function tearDown()
    {
        unset($this->seoMetadata);
    }

    public function testDefaultTitle()
    {
        $this->seoMetadata->setTitle('Title test');
        $this->translator->expects($this->once())
                         ->method('trans')
                         ->will($this->returnValue('Title test | Default Title'))
            ;
        $this->seoPresentation->updateSeoPage($this->document);

        $actualTitle = $this->pageService->getTitle();
        $this->assertEquals('Title test | Default Title', $actualTitle);
    }

    public function testDefaultDescription()
    {
        $this->seoMetadata->setMetaDescription('Test description.');
        $this->translator->expects($this->once())
                         ->method('trans')
                         ->will($this->returnValue('Default Description. Test description.'))
        ;
        $this->seoPresentation->updateSeoPage($this->document);

        $metas = $this->pageService->getMetas();
        $actualDescription = $metas['names']['description'][0];
        $this->assertEquals('Default Description. Test description.', $actualDescription);
    }

    public function testSettingKeywordsToSeoPage()
    {
        $this->seoMetadata->setMetaKeywords('key1, key2');
        //to set it here is the same as it was set in the sonata_seo settings
        $this->pageService->addMeta('names', 'keywords', 'default, other');
        $this->seoPresentation->updateSeoPage($this->document);
        $keywords = $this->pageService->getMetas();
        $this->assertEquals(
            'default, other, key1, key2',
            $keywords['names']['keywords'][0]
        );
    }

    public function testStrategies()
    {
        $this->translator->expects($this->any())
                         ->method('trans')
                         ->will($this->returnValue('translation strategy test'))
        ;

        $seoPresentation = new SeoPresentation($this->pageService, $this->translator, $this->configValues);
        $seoPresentation->addExtractor(new SeoOriginalUrlExtractor());
        $seoPresentation->addExtractor(new SeoTitleExtractor());
        $seoPresentation->addExtractor(new SeoDescriptionExtractor());
        $seoPresentation->updateSeoPage(new AllStrategiesDocument());



        $metas = $this->pageService->getMetas();
        $actualDescription = $metas['names']['description'][0];
        $actualTitle = $this->pageService->getTitle();
        $actualLink = $this->pageService->getLinkCanonical();

        $this->assertEquals('translation strategy test', $actualTitle);
        $this->assertEquals('translation strategy test', $actualDescription);
        $this->assertEquals('/test-route', $actualLink);
    }
}
