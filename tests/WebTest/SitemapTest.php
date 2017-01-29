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

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures([
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadSitemapData',
        ]);
        $this->client = $this->createClient();
    }

    /**
     * @param $format
     *
     * @dataProvider getFormats
     */
    public function testSitemap($format, $expected)
    {
        $this->client->request('GET', '/sitemap.'.$format);
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
        $content = $response->getContent();
        if ('html' === $format || 'xml' === $format) {
            $this->assertXmlStringEqualsXmlString($expected, $content);
        } else {
            $this->assertEquals($expected, $content);
        }
    }

    public function testDifferentSitemap()
    {
        $this->client->request('GET', '/frequent.json');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
        $this->assertContains('"always"', $response->getContent());
    }

    public function testSitemapNotFound()
    {
        $this->client->request('GET', '/nonexisting.json');
        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
        $this->assertContains('Unknown sitemap', $response->getContent());
    }

    public function getFormats()
    {
        return [
            [
                'html',
                '<ul class="cmf-sitemap">
                    <li class="indent-0">
                        <a href="http://localhost/sitemap-aware" title="Sitemap Aware Content">Sitemap Aware Content</a>
                    </li>
                    <li class="indent-0">
                        <a href="http://localhost/sitemap-aware-last-mod-date" title="Sitemap Aware Content last mod date">Sitemap Aware Content last mod date</a>
                    </li>
                    <li class="indent-0">
                        <a href="http://localhost/sitemap-aware-publish" title="Sitemap Aware Content publish">Sitemap Aware Content publish</a>
                    </li>
                </ul>',
            ],
            [
                'xml',
                '<?xml version="1.0"?>
                 <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
                     <url>
                         <loc>http://localhost/sitemap-aware</loc>
                         <changefreq>never</changefreq>
                         <xhtml:link href="http://localhost/sitemap-aware?_locale=de" hreflang="de" rel="alternate"/>
                     </url>
                     <url>
                         <loc>http://localhost/sitemap-aware-last-mod-date</loc>
                         <lastmod>2016-07-06T00:00:00+02:00</lastmod>
                         <changefreq>never</changefreq>
                     </url>
                     <url>
                         <loc>http://localhost/sitemap-aware-publish</loc>
                         <changefreq>never</changefreq>
                     </url>
                 </urlset>',
            ],
            [
                'json',
                '[{"loc":"http:\/\/localhost\/sitemap-aware","label":"Sitemap Aware Content","changefreq":"never","alternate_locales":[{"href":"http:\/\/localhost\/sitemap-aware?_locale=de","href_locale":"de"}],"depth":0},{"loc":"http:\/\/localhost\/sitemap-aware-last-mod-date","label":"Sitemap Aware Content last mod date","changefreq":"never","lastmod":"2016-07-06T00:00:00+02:00","alternate_locales":[],"depth":0},{"loc":"http:\/\/localhost\/sitemap-aware-publish","label":"Sitemap Aware Content publish","changefreq":"never","alternate_locales":[],"depth":0}]',
            ],
        ];
    }
}
