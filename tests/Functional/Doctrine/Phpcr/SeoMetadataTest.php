<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\Phpcr;

use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Doctrine\ODM\PHPCR\Document\Generic;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Fixtures\App\Document\SeoAwareContent;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class SeoMetadataTest extends BaseTestCase
{
    public function setUp()
    {
        (new PHPCRPurger($this->getDbManager('PHPCR')->getOm()))->purge();
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');
    }

    public function testSeoMetadataMapping()
    {
        $content = new SeoAwareContent();
        $content
            ->setTitle('Seo Aware test')
            ->setName('seo-aware')
            ->setParentDocument($this->dm->find(null, '/test'))
            ->setBody('Content for SeoAware Test')
        ;

        $seoMetadata = new SeoMetadata();
        $seoMetadata
            ->setTitle('Seo Title')
            ->setMetaDescription('Seo Description')
            ->setMetaKeywords('Seo, Keys')
            ->setOriginalUrl('/test')
            ->setExtraProperties(['og:title' => 'Extra title'])
            ->setExtraNames(['robots' => 'index, follow'])
            ->setExtraHttp(['content-type' => 'text/html'])
        ;

        $content->setSeoMetadata($seoMetadata);

        $this->dm->persist($content);
        $this->dm->flush();
        $this->dm->clear();

        $content = $this->dm->find(null, '/test/seo-aware');

        $this->assertNotNull($content);

        $persistedSeoMetadata = $content->getSeoMetadata();
        $this->assertEquals($seoMetadata->getTitle(), $persistedSeoMetadata->getTitle());
        $this->assertEquals($seoMetadata->getMetaDescription(), $persistedSeoMetadata->getMetaDescription());
        $this->assertEquals($seoMetadata->getMetaKeywords(), $persistedSeoMetadata->getMetaKeywords());
        $this->assertEquals($seoMetadata->getOriginalUrl(), $persistedSeoMetadata->getOriginalUrl());
        $this->assertEquals($seoMetadata->getExtraProperties(), $persistedSeoMetadata->getExtraProperties());
        $this->assertEquals($seoMetadata->getExtraNames(), $persistedSeoMetadata->getExtraNames());
        $this->assertEquals($seoMetadata->getExtraHttp(), $persistedSeoMetadata->getExtraHttp());
    }

    /**
     * @expectedException \Doctrine\ODM\PHPCR\Exception\OutOfBoundsException
     * @expectedExceptionMessage It cannot have children
     */
    public function testAddSeoMetadataChild()
    {
        $seoMetadata = new SeoMetadata();
        $seoMetadata->setName('seo-metadata');
        $seoMetadata->setParentDocument($this->dm->find(null, '/test'));
        $this->dm->persist($seoMetadata);

        $document = new Generic();
        $document->setParentDocument($seoMetadata);
        $document->setNodename('invalid');
        $this->dm->persist($document);

        $this->dm->flush();
    }
}
