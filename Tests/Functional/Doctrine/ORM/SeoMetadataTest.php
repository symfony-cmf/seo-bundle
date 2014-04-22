<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\ORM;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Model\SeoAwareOrmContent;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase as ComponentBaseTestCase;

class SeoMetadataTest extends ComponentBaseTestCase
{
    protected function getKernelConfiguration()
    {
        return array(
            'environment' => 'orm',
        );
    }

    protected function clearDb($model)
    {
        if (is_array($model)) {
            foreach ($model as $singleModel) {
                $this->clearDb($singleModel);
            }
        }

        $items = $this->getDm()->getRepository($model)->findAll();

        foreach ($items as $item) {
            $this->getDm()->remove($item);
        }

        $this->getDm()->flush();
    }

    protected function getDm()
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
            'extraProperties'   => array('og:title'     => 'Extra title'),
            'extraNames'       => array('robots'       => 'index, follow'),
            'extraHttp'       => array('content-type' => 'text/html'),
        );

        $seoMetadata = new SeoMetadata();

        foreach ($data as $key => $value) {
            $seoMetadata->{'set'.ucfirst($key)}($value);
        }

        $content->setSeoMetadata($seoMetadata);
        $this->getDm()->persist($content);
        $this->getDm()->flush();
        $this->getDm()->clear();

        $content = $this->getDm()
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
