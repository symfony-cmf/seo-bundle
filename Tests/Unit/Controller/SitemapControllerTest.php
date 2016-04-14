<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Controller;

use Symfony\Cmf\Bundle\SeoBundle\Controller\SitemapController;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocale;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProvider;
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
     * @var UrlInformationProvider
     */
    private $provider;

    /**
     * @var SitemapController
     */
    private $controller;

    public function setUp()
    {
        $this->provider = $this
            ->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $this->provider
            ->expects($this->any())
            ->method('getUrlInformation')
            ->will($this->returnValue($this->createUrlInformation()));

        $this->templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $this->controller = new SitemapController(
            $this->provider,
            $this->templating,
            array(
                'test' => array(
                    'templates' => array(
                        'xml' => 'CmfSeoBundle:Sitemap:index.xml.twig',
                        'html' => 'CmfSeoBundle:Sitemap:index.html.twig',
                    ),
                ),
            )
        );
    }

    public function testRequestJson()
    {
        /** @var Response $response */
        $response = $this->controller->indexAction('json', 'test');
        $expected = array(
            array(
                'loc' => 'http://www.test-alternate-locale.de',
                'label' => 'Test alternate locale',
                'changefreq' => 'never',
                'lastmod' => '2014-11-07T00:00:00+01:00',
                'priority' => 0.85,
                'alternate_locales' => array(
                    array('href' => 'http://www.test-alternate-locale.com', 'href_locale' => 'en'),
                ),
            ),
            array(
                'loc' => 'http://www.test-domain.de',
                'label' => 'Test label',
                'changefreq' => 'always',
                'lastmod' => '2014-11-06T00:00:00+01:00',
                'priority' => 0.85,
                'alternate_locales' => array(),
            ),
        );

        $this->assertEquals($expected, json_decode($response->getContent(), true));
    }

    public function testRequestXml()
    {
        $this->templating->expects($this->once())
            ->method('render')
            ->with($this->equalTo('CmfSeoBundle:Sitemap:index.xml.twig'), $this->anything())
            ->will($this->returnValue('some-xml-string'));

        /** @var Response $response */
        $response = $this->controller->indexAction('xml', 'test');

        $this->assertEquals('some-xml-string', $response->getContent());
    }

    public function testRequestHtml()
    {
        $expectedResponse = new Response('some-html-string');
        $this->templating->expects($this->once())->method('render')->will($this->returnValue($expectedResponse));

        /** @var Response $response */
        $response = $this->controller->indexAction('html', 'test');

        $this->assertEquals($expectedResponse, $response->getContent());
    }

    private function createUrlInformation()
    {
        $resultList = array();

        $urlInformation = new UrlInformation();
        $urlInformation
            ->setLocation('http://www.test-alternate-locale.de')
            ->setChangeFrequency('never')
            ->setLabel('Test alternate locale')
            ->setPriority(0.85)
            ->setLastModification(new \DateTime('2014-11-07', new \DateTimeZone('Europe/Berlin')))
        ;
        $alternateLocale = new AlternateLocale('http://www.test-alternate-locale.com', 'en');
        $urlInformation->addAlternateLocale($alternateLocale);
        $resultList[] = $urlInformation;

        $urlInformation = new UrlInformation();
        $urlInformation
            ->setLocation('http://www.test-domain.de')
            ->setChangeFrequency('always')
            ->setLabel('Test label')
            ->setPriority(0.85)
            ->setLastModification(new \DateTime('2014-11-06', new \DateTimeZone('Europe/Berlin')))
        ;
        $resultList[] = $urlInformation;

        return $resultList;
    }
}
