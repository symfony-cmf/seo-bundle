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

use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class SeoMetadataTest extends BaseTestCase
{
    public function setUp()
    {
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
            ->setExtraProperties(array('og:title' => 'Extra title'))
            ->setExtraNames(array('robots' => 'index, follow'))
            ->setExtraHttp(array('content-type' => 'text/html'))
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
}
