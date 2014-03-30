<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Tests\WebTest\Admin;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * This test will cover all behavior with the provides admin extension.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoContentAdminExtensionTest extends BaseTestCase
{
    /** @var  Client */
    private $client;

    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData',
        ));
        $this->client = $this->createClient();
    }

    public function testAdminDashboard()
    {
        $crawler = $this->client->request('GET', '/admin/dashboard');
        $res = $this->client->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("SeoContent")'));
    }

    public function testAdminExtensionExists()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/seo/seoawarecontent/list');
        $res = $this->client->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("content-1")'));

        //test the exist of the labels
        $this->assertCount(1, $crawler->filter('html:contains("ID")'));
        $this->assertCount(1, $crawler->filter('html:contains("Title")'));
        $this->assertCount(1, $crawler->filter('html:contains("List SeoContent")'));
    }

    public function testItemEditView()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/seo/seoawarecontent/test/content/content-1/edit');
        $res = $this->client->getResponse();

        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("Title")'));
        $this->assertCount(1, $crawler->filter('html:contains("Original url")'));
        $this->assertCount(1, $crawler->filter('html:contains("Meta description")'));
        $this->assertCount(1, $crawler->filter('html:contains("Meta keywords")'));
    }
}
