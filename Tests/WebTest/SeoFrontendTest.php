<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\WebTest;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
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
    /** @var  Client */
    private $client;

    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
                'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData',
            ));
        $this->client = $this->createClient();
    }

    public function testTitle()
    {
        $crawler = $this->client->request('GET', '/content-1');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Content 1")'));
    }
}
