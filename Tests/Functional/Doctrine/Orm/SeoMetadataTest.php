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
        $content->setTitle('Seo Aware test');
        $content->setBody('Content for SeoAware Test');

        $data = array(
            'title'           => 'Seo Title',
            'metaDescription' => 'Seo Description',
            'metaKeywords'    => 'Seo, Keys',
            'originalUrl'     => '/test',
            'extraProperties' => array('og:title'     => 'Extra title'),
            'extraNames'      => array('robots'       => 'index, follow'),
            'extraHttp'       => array('content-type' => 'text/html'),
        );

        $seoMetadata = new SeoMetadata();

        foreach ($data as $key => $value) {
            $seoMetadata->{'set'.ucfirst($key)}($value);
        }

        $content->setSeoMetadata($seoMetadata);

        $this->getEm()->persist($content);
        $this->getEm()->flush();
        $this->getEm()->clear();

        $content = $this->getEm()
                        ->getRepository('Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Model\SeoAwareOrmContent')
                        ->findBy(array('name' => 'Seo Aware test'));

        $this->assertNotNull($content);

        $persistedSeoMetadata = $content->getSeoMetadata();

        foreach ($data as $key => $value) {
            $v = $persistedSeoMetadata->{'get'.ucfirst($key)}($value);

            $this->assertEquals($value, $v);
        }
    }
}
