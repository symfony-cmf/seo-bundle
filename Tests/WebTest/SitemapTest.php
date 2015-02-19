<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\WebTest;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapTest extends BaseTestCase
{
    /** @var  Client */
    private $client;

    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadSitemapData',
        ));
        $this->client = $this->createClient();
    }

    /**
     * @param $format
     *
     * @dataProvider getFormats
     */
    public function testSitemap($format)
    {
        $this->client->request('GET', '/sitemap.'.$format);
        $res = $this->client->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $content = $res->getContent();
        if ('html' === $format || 'xml' === $format) {
            $this->assertXmlStringEqualsXmlString($this->getContent($format), $content);
        } else {
            $this->assertEquals($this->getContent($format), $content);
        }
    }

    public function getFormats()
    {
        return array(
            array('html'), array('xml'), array('json')
        );
    }

    public function getContent($format)
    {
        $data = array(
            'html' => '<ul class="cmf-sitemap">
                          <li>
                            <a href="http://localhost/sitemap-aware" title="Sitemap Aware Content">Sitemap Aware Content</a>
                          </li>
                          <li>
                            <a href="http://localhost/sitemap-aware-publish" title="Sitemap Aware Content publish">Sitemap Aware Content publish</a>
                          </li>
                       </ul>',
            'xml'  => '<?xml version="1.0"?>
                        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
                          <url>
                            <loc>http://localhost/sitemap-aware</loc>
                            <lastmod/>
                            <changefreq>never</changefreq>
                            <xhtml:link href="http://localhost/sitemap-aware?_locale=de" hreflang="de" rel="alternate"/>
                          </url>
                          <url>
                            <loc>http://localhost/sitemap-aware-publish</loc>
                            <lastmod/>
                            <changefreq>never</changefreq>
                          </url>
                        </urlset>',
            'json' => '[{"loc":"http:\/\/localhost\/sitemap-aware","label":"Sitemap Aware Content","changefreq":"never","lastmod":null,"priority":null,"alternate_locales":[{"href":"http:\/\/localhost\/sitemap-aware?_locale=de","href_locale":"de"}]},{"loc":"http:\/\/localhost\/sitemap-aware-publish","label":"Sitemap Aware Content publish","changefreq":"never","lastmod":null,"priority":null,"alternate_locales":[]}]',
        );

        return $data[$format];
    }
}
