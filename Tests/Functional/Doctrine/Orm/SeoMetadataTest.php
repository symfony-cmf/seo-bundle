<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\Orm;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Entity\SeoAwareOrmContent;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class SeoMetadataTest extends BaseTestCase
{
    protected function getKernelConfiguration()
    {
        return array(
            'environment' => 'orm',
        );
    }

    protected function getEm()
    {
        return $this->db('ORM')->getOm();
    }

    public function testSeoMetadata()
    {
        $content = new SeoAwareOrmContent();
        $content
            ->setTitle('Seo Aware test')
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

        $this->getEm()->persist($content);
        $this->getEm()->flush();
        $this->getEm()->clear();

        $content = $this->getEm()
                        ->getRepository('Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Entity\SeoAwareOrmContent')
                        ->findOneByTitle('Seo Aware test');

        $this->assertNotNull($content);

        $persistedSeoMetadata = $content->getSeoMetadata();
        $this->assertEquals($seoMetadata, $persistedSeoMetadata);
    }
}
