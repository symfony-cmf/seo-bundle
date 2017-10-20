<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\WebTest;

use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\DomCrawler\Crawler;

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
        (new PHPCRPurger($this->getDbManager('PHPCR')->getOm()))->purge();
        $this->db('PHPCR')->loadFixtures([
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData',
        ]);
    }

    /**
     * This test is without any setting in sonata_seo just cmf data.
     */
    public function testDefaultUsage()
    {
        $crawler = $this->getClient()->request('GET', '/content/content-1');
        $res = $this->getClient()->getResponse();

        $this->assertSame(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Content 1")'));

        //test the title
        $titleCrawler = $crawler->filter('head > title');
        $this->assertSame('Default | Title content 1', $titleCrawler->text());

        //test the meta tag entries
        $metaCrawler = $crawler->filter('head > meta')->reduce(function (Crawler $node) {
            $nameValue = $node->attr('name');

            return 'description' === $nameValue || 'keywords' === $nameValue;
        });

        $actualMeta = $metaCrawler->extract('content', 'content');
        $expectedMeta = [
            'testkey, content1, content',
            'Default description. Description of content 1.',
        ];
        $this->assertSame($expectedMeta, $actualMeta);

        //test the setting of canonical link
        $linkCrawler = $crawler->filter('head > link')->reduce(function (Crawler $node) {
            return SeoPresentation::ORIGINAL_URL_CANONICAL === $node->attr('rel');
        });
        $this->assertSame('/to/original', $linkCrawler->eq(0)->attr('href'));
    }

    public function testExtractors()
    {
        $crawler = $this->getClient()->request('GET', '/content/strategy-content');
        $res = $this->getClient()->getResponse();

        $this->assertSame(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("content of strategy test.")'));

        //test the title
        $titleCrawler = $crawler->filter('head > title');
        $this->assertSame('Default | Strategy title', $titleCrawler->text());

        //test the meta tag entries
        $metaCrawler = $crawler->filter('head > meta')->reduce(function (Crawler $node) {
            $nameValue = $node->attr('name');

            return 'description' === $nameValue || 'keywords' === $nameValue;
        });

        $actualMeta = $metaCrawler->extract('content', 'content');
        $expectedMeta = [
            'testkey, test, key',
            'Default description. content of strategy test. ...',
        ];
        $this->assertSame($expectedMeta, $actualMeta);

        //test the setting of canonical link
        $linkCrawler = $crawler->filter('head > link')->reduce(function (Crawler $node) {
            return SeoPresentation::ORIGINAL_URL_CANONICAL === $node->attr('rel');
        });
        $this->assertSame('/home', $linkCrawler->eq(0)->attr('href'));
    }

    /**
     * @dataProvider getExtraProperties
     */
    public function testExtraProperties($expectedType, $expectedKey, $expectedValue)
    {
        $crawler = $this->getClient()->request('GET', '/content/content-extra');
        $res = $this->getClient()->getResponse();

        $this->assertSame(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Content extra")'));

        //test the meta tag entries
        $metaCrawler = $crawler->filter('head > meta')->reduce(function (Crawler $node) use ($expectedType, $expectedKey) {
            return $expectedKey === $node->attr($expectedType);
        });

        $actualMeta = $metaCrawler->extract('content', 'content');
        $actualMeta = reset($actualMeta);
        $this->assertSame($expectedValue, $actualMeta);
    }

    public function getExtraProperties()
    {
        return [
            ['property', 'og:title', 'extra title'],
            ['name', 'robots', 'index, follow'],
            ['http-equiv', 'Content-Type', 'text/html; charset=utf-8'],
        ];
    }

    public function testAlternateLanguages()
    {
        $crawler = $this->getClient()->request('GET', '/en/alternate-locale-content');
        $res = $this->getClient()->getResponse();

        $this->assertSame(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Alternate locale content")'));

        $linkCrawler = $crawler->filter('head > link');
        $expectedArray = [['alternate', 'http://localhost/de/alternate-locale-content', 'de']];
        $this->assertSame($expectedArray, $linkCrawler->extract(['rel', 'href', 'hreflang']));

        $crawler = $this->getClient()->request('GET', '/de/alternate-locale-content');
        $res = $this->getClient()->getResponse();

        $this->assertSame(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Alternative Sprachen")'));

        $linkCrawler = $crawler->filter('head > link');
        $expectedArray = [['alternate', 'http://localhost/en/alternate-locale-content', 'en']];
        $this->assertSame($expectedArray, $linkCrawler->extract(['rel', 'href', 'hreflang']));
    }

    public function testErrorHandling()
    {
        $crawler = $this->client->request('GET', '/content/content-1/content-depp');
        $res = $this->client->getResponse();

        $this->assertSame(404, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("Exception-Test")')); // the configured template was chosen
        $this->assertCount(1, $crawler->filter('html:contains("parent - content-1")'));
        $this->assertCount(1, $crawler->filter('html:contains("sibling - content-deeper")'));
    }

    public function testErrorHandlingInvalidPhpcrPath()
    {
        $this->client->request('GET', '/content/content-1/content[a]b/sub?bla=blup');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testErrorHandlingForExcludedPath()
    {
        $crawler = $this->client->request('GET', '/content/content-1/content-excluded');
        $res = $this->client->getResponse();

        $this->assertSame(404, $res->getStatusCode());

        $this->assertCount(0, $crawler->filter('h1:contains("Exception-Test")')); // the default template was chosen
        $this->assertCount(1, $crawler->filter('html:contains("No route found for")'));
    }

    public function testContentLanguageHeader()
    {
        $crawler = $this->getClient()->request('GET', '/en/alternate-locale-content');
        $res = $this->getClient()->getResponse();

        $this->assertSame(200, $res->getStatusCode());
        $this->assertSame('en', $res->headers->get('Content-Language'));
    }
}
