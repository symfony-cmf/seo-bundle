<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\Phpcr;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapUrlInformationProvider;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocale;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapUrlInformationProviderTest extends BaseTestCase
{
    /**
     * @var DocumentManager
     */
    protected $dm;
    protected $base;

    /**
     * @var SitemapUrlInformationProvider
     */
    protected $provider;
    protected $alternateLocaleProvider;
    protected $logger;
    protected $presentation;

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');

        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadSitemapData',
        ));

        $this->logger = $this->getMock('Psr\Log\LoggerInterface');
        $this->presentation = $this
            ->getMockBuilder('\Symfony\Cmf\Bundle\SeoBundle\SeoPresentation')
            ->disableOriginalConstructor()
            ->getMock();

        $this->provider = new SitemapUrlInformationProvider(
            $this->dm,
            $this->getContainer()->get('router'),
            'always',
            $this->logger,
            $this->presentation,
            $this->getContainer()->get('cmf_core.publish_workflow.checker')
        );
        $this->alternateLocaleProvider = $this
            ->getMock('\Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface');
        $this->provider->setAlternateLocaleProvider($this->alternateLocaleProvider);

        $alternateLocale = new AlternateLocale('test', 'de');
        $this->alternateLocaleProvider
            ->expects($this->any())
            ->method('createForContent')
            ->will($this->returnValue(new ArrayCollection(array($alternateLocale))));
    }

    public function testRouteGeneration()
    {
        // expected methods/class
        $seoMetadata = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface');
        $this->presentation
            ->expects($this->any())
            ->method('getSeoMetadata')
            ->will($this->returnValue($seoMetadata));
        $seoMetadata->expects($this->any())->method('getTitle')->will($this->returnValue('test-title'));

        $routeInformation = $this->provider->getUrlInformation();

        $this->assertCount(2, $routeInformation);
        $actualValues = array();
        foreach ($routeInformation as $information) {
            $actualValues[] = $information->toArray();
        }
        $expectedValues = array(
            array(
                'loc' => 'http://localhost/sitemap-aware',
                'label' => 'test-title',
                'changefreq' => 'always',
                'lastmod'  => '',
                'priority' => '',
                'alternate_locales' => array(
                    array('href' => 'test', 'href_locale' => 'de'),
                ),
            ),
            array(
                'loc' => 'http://localhost/sitemap-aware-publish',
                'label' => 'test-title',
                'changefreq' => 'always',
                'lastmod'  => '',
                'priority' => '',
                'alternate_locales' => array(
                    array('href' => 'test', 'href_locale' => 'de'),
                ),
            ),
        );
        $this->assertEquals($expectedValues, $actualValues);
    }
}
