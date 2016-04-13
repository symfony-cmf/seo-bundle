<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\WebTest\Admin;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * This test will cover all behavior with the provides admin extension.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoContentAdminExtensionTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData',
        ));
    }

    public function testAdminDashboard()
    {
        $this->getClient()->request('GET', '/admin/dashboard');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
    }

    public function testAdminExtensionExists()
    {
        $crawler = $this->getClient()->request('GET', '/admin/cmf/seo/seoawarecontent/list');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("content-1")'));
    }

    public function testItemEditView()
    {
        $crawler = $this->getClient()->request('GET', '/admin/cmf/seo/seoawarecontent/test/content/content-1/edit');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("SEO")'));
        $this->assertCount(1, $crawler->filter('html:contains("Page title")'));
        $this->assertCount(1, $crawler->filter('html:contains("Original URL")'));
        $this->assertCount(1, $crawler->filter('html:contains("description")'));
        $this->assertCount(1, $crawler->filter('html:contains("keywords")'));
    }

    public function testExtraPropertyEditView()
    {
        $crawler = $this->getClient()->request('GET', '/admin/cmf/seo/seoawarecontent/test/content/content-extra/edit');
        $res = $this->getClient()->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Key")'));
        $this->assertCount(1, $crawler->filter('html:contains("Value")'));
    }

    public function testItemCreate()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/seo/seoawarecontent/create');
        $res = $this->client->getResponse();

        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("SEO")'));
        $this->assertCount(1, $crawler->filter('html:contains("Page title")'));
        $this->assertCount(1, $crawler->filter('html:contains("Original URL")'));
        $this->assertCount(1, $crawler->filter('html:contains("description")'));
        $this->assertCount(1, $crawler->filter('html:contains("keywords")'));
    }
}
