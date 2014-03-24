<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit;

use Sonata\SeoBundle\Seo\SeoPage;
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

    public function setUp()
    {
        //set up the SUT
        $this->pageService = new SeoPage();
        $this->translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $defaultSeoParameters = array(
            'translation_domain'    => null,
            'title_key'             => 'title_key',
            'description_key'       => 'description_key',
            'original_route_pattern'=> 'canonical'
        );

        $this->seoPresentation = new SeoPresentation(
            $this->pageService,
            $this->translator,
            $defaultSeoParameters
        );

        $this->seoMetadata = new SeoMetadata();
    }

    public function tearDown()
    {
        unset($this->seoMetadata);
    }

    public function testSettingKeywordsToSeoPage()
    {
        $this->markTestSkipped('need to be refactored');

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
        $this->pageService->addMeta('names', 'description', 'Default description');
        $defaultSeoParameters = array(
            'translation_domain'    => null,
            'title_key'             => 'title_key',
            'description_key'       => 'description_key',
            'original_route_pattern'=> 'canonical'
        );
        $seoPresentation = new SeoPresentation($this->pageService, $this->translator, $defaultSeoParameters);
        $seoPresentation->addExtractor(new SeoOriginalUrlExtractor());
        $seoPresentation->addExtractor(new SeoTitleExtractor());
        $seoPresentation->addExtractor(new SeoDescriptionExtractor());

        $seoPresentation->updateSeoPage(new AllStrategiesDocument());

        $metas = $this->pageService->getMetas();
        $actualDescription = $metas['names']['description'][0];
        $actualTitle = $this->pageService->getTitle();
        $actualLink = $this->pageService->getLinkCanonical();

        $this->markTestSkipped('need to be refactored');

        $this->assertEquals('Test title | Default title', $actualTitle);
        $this->assertEquals('Default description. Test Description.', $actualDescription);
        $this->assertEquals('/test-route', $actualLink);
    }
}
