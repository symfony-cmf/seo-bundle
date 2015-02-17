<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Controller;

use Symfony\Cmf\Bundle\SeoBundle\Controller\SitemapController;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocale;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\ChainProvider;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var UrlInformationProviderInterface
     */
    private $provider;

    /**
     * @var SitemapController
     */
    private $controller;

    public function setUp()
    {
        $this->provider = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface');
        $this->createRoutes();

        $this->templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $this->controller = new SitemapController(
            $this->provider,
            $this->templating,
            array(
                'xml'  => 'CmfSeoBundle:Sitemap:index.xml.twig',
                'html' => 'CmfSeoBundle:Sitemap:index.html.twig',
            )
        );
    }

    public function testRequestJson()
    {
        /** @var Response $response */
        $response = $this->controller->indexAction('json');
        $expected = array(
            array(
                'loc'               => 'http://www.test-alternate-locale.de',
                'label'             => 'Test alternate locale',
                'changefreq'        => 'never',
                'lastmod'           => '2014-11-07T00:00:00+01:00',
                'priority'          => 0.85,
                'alternate_locales' => array(
                    array('href' => 'http://www.test-alternate-locale.com', 'href_locale' => 'en')
                ),
            ),
            array(
                'loc'               => 'http://www.test-domain.de',
                'label'             => 'Test label',
                'changefreq'        => 'always',
                'lastmod'           => '2014-11-06T00:00:00+01:00',
                'priority'          => 0.85,
                'alternate_locales' => array(),
            ),
        );

        $this->assertEquals($expected, json_decode($response->getContent(), true));
    }

    public function testRequestXml()
    {
        $response = new Response('some-xml-string');
        $this->templating->expects($this->once())->method('render')->will($this->returnValue($response));

        /** @var Response $response */
        $response = $this->controller->indexAction('xml');

        $this->assertEquals(new Response('some-xml-string'), $response->getContent());
    }

    public function testRequestHtml()
    {
        $expectedResponse = new Response('some-html-string');
        $this->templating->expects($this->once())->method('render')->will($this->returnValue($expectedResponse));

        /** @var Response $response */
        $response = $this->controller->indexAction('html');

        $this->assertEquals($expectedResponse, $response->getContent());
    }

    private function createRoutes()
    {
        $urls = array();

        $simpleUrl = new UrlInformation();
        $simpleUrl
            ->setLocation('http://www.test-domain.de')
            ->setChangeFrequency('always')
            ->setLabel('Test label')
            ->setPriority(0.85)
            ->setLastModification(new \DateTime('2014-11-06', new \DateTimeZone('Europe/Berlin')))
        ;

        $urlWithAlternateLocale = new UrlInformation();
        $urlWithAlternateLocale
            ->setLocation('http://www.test-alternate-locale.de')
            ->setChangeFrequency('never')
            ->setLabel('Test alternate locale')
            ->setPriority(0.85)
            ->setLastModification(new \DateTime('2014-11-07', new \DateTimeZone('Europe/Berlin')))
        ;
        $alternateLocale = new AlternateLocale('http://www.test-alternate-locale.com', 'en');
        $urlWithAlternateLocale->addAlternateLocale($alternateLocale);

        $urls[] = $urlWithAlternateLocale;
        $urls[] = $simpleUrl;

        $this->provider->expects($this->any())->method('getUrlInformation')->will($this->returnValue($urls));
    }

    private function getFileContent($type)
    {
        $basePath = __DIR__.'/../../Resources/Fixtures/sitemap/sitemap';

        return file_get_contents($basePath.'.'.$type);
    }
}
