<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2013 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ContentBundle\Tests\WebTest\Admin\ContentNodeAdminTest;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class StaticContentAdminTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\ContentBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData',
        ));
        $this->client = $this->createClient();
    }

    public function testContentList()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/content/staticcontent/list');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('html:contains("Content 1")'));
    }

    public function testContentEdit()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/content/staticcontent/test/contents/content-1/edit');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(1, $crawler->filter('input[value="content-1"]'));
    }

    public function testContentCreate()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/content/staticcontent/create');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $button = $crawler->selectButton('Create');
        $form = $button->form();
        $node = $form->getFormNode();
        $actionUrl = $node->getAttribute('action');
        $uniqId = substr(strchr($actionUrl, '='), 1);

        $form[$uniqId.'[parent]'] = '/test/contents';
        $form[$uniqId.'[name]'] = 'foo-test';
        $form[$uniqId.'[title]'] = 'Foo Test';
        $form[$uniqId.'[body]'] = 'Foo Test';

        $this->client->submit($form);
        $res = $this->client->getResponse();

        // If we have a 302 redirect, then all is well
        $this->assertEquals(302, $res->getStatusCode());
    }
}
