<?php


namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\PHPCR;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\Extra;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class SeoMetadataTest extends BaseTestCase {

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');
    }

    public function testSeoMetadataMapping()
    {
        $content = new SeoAwareContent();
        $content->setTitle('Seo Aware test');
        $content->setName('seo-aware');
        $content->setParentDocument($this->dm->find(null, '/test'));
        $content->setBody('Content for SeoAware Test');

        $data = array(
            'title'           => 'Seo Title',
            'metaDescription' => 'Seo Description',
            'metaKeywords'    => 'Seo, Keys',
            'originalUrl'     => '/test',
            'extraProperty'   => array('og:title'     => 'Extra title'),
            'extraName'       => array('robots'       => 'index, follow'),
            'extraHttp'       => array('content-type' => 'text/html'),
        );

        $seoMetadata = new SeoMetadata();
        $seoMetadata->setParentDocument($content);
        $seoMetadata->setName('seo-metadata');

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $extraKey => $extraValue) {
                    $seoMetadata->{'add'.ucfirst($key)}(new Extra($extraKey, $extraValue));
                }

            } else {
                $seoMetadata->{'set'.ucfirst($key)}($value);
            }
        }

        $this->dm->persist($seoMetadata);

        $content->setSeoMetadata($seoMetadata);
        $this->dm->persist($content);
        $this->dm->flush();
        $this->dm->clear();

        $content = $this->dm->find(null, '/test/seo-aware');

        $this->assertNotNull($content);

        foreach ($data as $key => $value) {
            if ('extraProperty' === $key) {
                $v = $seoMetadata->getExtraProperties();
            } elseif ('extraName' === $key) {
                $v = $seoMetadata->getExtraNames();
            } else {
                $v = $seoMetadata->{'get'.ucfirst($key)}($value);
            }

            $this->assertEquals($value, $v);
        }
    }
}
 