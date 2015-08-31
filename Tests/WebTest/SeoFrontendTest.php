<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\WebTest;

use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Client;

/**
 * This test will cover all current frontend stuff.
 *
 * - title has to be a combination of the content title and the default one
 * - the description is the document description
 * - keywords, contain the default ones set in the sonat_seo section and the ones from doc
 * - canonical link has to exist
 *
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoFrontendTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData',
        ));
    }

    /**
     * This test is without any setting in sonata_seo just cmf data.
     */
    public function testDefaultUsage()
    {
        $crawler = $this->getClient()->request('GET', '/content/content-1');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Content 1")'));

        //test the title
        $titleCrawler = $crawler->filter('head > title');
        $this->assertEquals('Default | Title content 1', $titleCrawler->text());

        //test the meta tag entries
        $metaCrawler = $crawler->filter('head > meta')->reduce(function (Crawler $node) {
                $nameValue = $node->attr('name');

                return 'title' === $nameValue || 'description' === $nameValue ||'keywords' === $nameValue;
        });

        $actualMeta = $metaCrawler->extract('content', 'content');
        $expectedMeta = array(
            'testkey, content1, content',
            'Default | Title content 1',
            'Default description. Description of content 1.',
        );
        $this->assertEquals($expectedMeta, $actualMeta);

        //test the setting of canonical link
        $linkCrawler = $crawler->filter('head > link')->reduce(function (Crawler $node) {
            return SeoPresentation::ORIGINAL_URL_CANONICAL === $node->attr('rel');
        });
        $this->assertEquals('/to/original', $linkCrawler->eq(0)->attr('href'));
    }

    public function testExtractors()
    {
        $crawler = $this->getClient()->request('GET', '/content/strategy-content');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("content of strategy test.")'));

        //test the title
        $titleCrawler = $crawler->filter('head > title');
        $this->assertEquals('Default | Strategy title', $titleCrawler->text());

        //test the meta tag entries
        $metaCrawler = $crawler->filter('head > meta')->reduce(function (Crawler $node) {
            $nameValue = $node->attr('name');

            return 'title' === $nameValue || 'description' === $nameValue ||'keywords' === $nameValue;
        });

        $actualMeta = $metaCrawler->extract('content', 'content');
        $expectedMeta = array(
            'testkey, test, key',
            'Default | Strategy title',
            'Default description. content of strategy test. ...',
        );
        $this->assertEquals($expectedMeta, $actualMeta);

        //test the setting of canonical link
        $linkCrawler = $crawler->filter('head > link')->reduce(function (Crawler $node) {
            return SeoPresentation::ORIGINAL_URL_CANONICAL === $node->attr('rel');
        });
        $this->assertEquals('/home', $linkCrawler->eq(0)->attr('href'));
    }

    /**
     * @dataProvider getExtraProperties
     */
    public function testExtraProperties($expectedType, $expectedKey, $expectedValue)
    {
        $crawler = $this->getClient()->request('GET', '/content/content-extra');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Content extra")'));

        //test the meta tag entries
        $metaCrawler = $crawler->filter('head > meta')->reduce(function (Crawler $node) use ($expectedType, $expectedKey) {
            return $expectedKey === $node->attr($expectedType);
        });

        $actualMeta = $metaCrawler->extract('content', 'content');
        $actualMeta = reset($actualMeta);
        $this->assertEquals($expectedValue, $actualMeta);
    }

    public function getExtraProperties()
    {
        return array(
            array('property', 'og:title', 'extra title'),
            array('name', 'robots', 'index, follow'),
            array('http-equiv', 'Content-Type', 'text/html; charset=utf-8'),
        );
    }

    public function testAlternateLanguages()
    {
        $crawler = $this->getClient()->request('GET', '/en/alternate-locale-content');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Alternate locale content")'));

        $linkCrawler = $crawler->filter('head > link');
        $expectedArray = array(array('alternate', 'http://localhost/de/alternate-locale-content', 'de'));
        $this->assertEquals($expectedArray, $linkCrawler->extract(array('rel', 'href', 'hreflang')));

        $crawler = $this->getClient()->request('GET', '/de/alternate-locale-content');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Alternative Sprachen")'));

        $linkCrawler = $crawler->filter('head > link');
        $expectedArray = array(array('alternate', 'http://localhost/en/alternate-locale-content', 'en'));
        $this->assertEquals($expectedArray, $linkCrawler->extract(array('rel', 'href', 'hreflang')));
    }
}
