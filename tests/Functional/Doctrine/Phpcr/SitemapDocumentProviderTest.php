<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapDocumentProvider;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class SitemapDocumentProviderTest extends BaseTestCase
{
    /**
     * @var SitemapDocumentProvider
     */
    private $documentProvider;

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadSitemapData',
        ));

        $this->documentProvider = new SitemapDocumentProvider($this->dm);
    }

    public function testDocumentOrder()
    {
        $documents = $this->documentProvider->load('default');

        $paths = array();
        foreach ($documents as $document) {
            $paths[] = $document->getId();
        }

        $this->assertEquals(
            array(
                '/test/content/sitemap-aware',
                '/test/content/sitemap-aware-last-mod-date',
                '/test/content/sitemap-aware-non-publish',
                '/test/content/sitemap-aware-publish',
            ),
            $paths
        );
    }
}
