<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadataPersistenceTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');
    }

    public function testBaseSettings()
    {
        //property values for the seo metadata
        $metaData = array(
            'metaDescription'   => 'Content description.',
            'title'             => 'Content title',
            'metaKeywords'      => 'key1, key2',
            'originalUrl'       => '/test/route',
        );

        $metaDataClass = new SeoMetadata();
        $metaRefl = new \ReflectionClass($metaDataClass);

        foreach ($metaData as $key => $value) {
            $refl = new \ReflectionClass($metaDataClass);
            $prop = $refl->getProperty($key);
            $prop->setAccessible(true);
            $prop->setValue($metaDataClass, $value);
        }

        //properties for the document itself
        $documentData = array(
            'name'          => 'test-title',
            'title'         => 'test-title',
            'body'          => 'test-body',
            'seoMetadata'   => $metaDataClass,
        );

        $content = new SeoAwareContent();
        $refl = new \ReflectionClass($content);
        $content->setParentDocument($this->base);
        foreach ($documentData as $key => $value) {
            $refl = new \ReflectionClass($content);
            $prop = $refl->getProperty($key);
            $prop->setAccessible(true);
            $prop->setValue($content, $value);
        }

        $this->dm->persist($content);
        $this->dm->flush();
        $this->dm->clear();

        $content = $this->dm->find(null, '/test/test-title');

        $this->assertNotNull($content);

        foreach ($documentData as $key => $value) {
            $prop = $refl->getProperty($key);
            $prop->setAccessible(true);
            $v = $prop->getValue($content);

            if (!is_object($value)) {
                $this->assertEquals($value, $v);
            }
        }
    }
}
