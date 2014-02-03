<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2013 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ContentBundle\Tests\Functional\Doctrine\Phpcr;

use Cmf\Bundle\SeoBundle\Document\PHPCR\SeoAwareContent;
use Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class SeoAwareContentTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');
    }

    public function testSeoAwareContent()
    {
        $seoStuff = new SeoMetadata();
        $seoStuff->setTitle('seo-test-title');
        $seoStuff->setMetaKeywords('key1, key2');

        $data = array(
            'name' => 'test-node',
            'title' => 'test-title',
            'body' => 'test-body',
            'seoStuff'  => $seoStuff
        );

        $content = new SeoAwareContent();
        $refl = new \ReflectionClass($content);

        $content->setParent($this->base);

        foreach ($data as $key => $value) {
            $refl = new \ReflectionClass($content);
            $prop = $refl->getProperty($key);
            $prop->setAccessible(true);
            $prop->setValue($content, $value);
        }

        $this->dm->persist($content);
        $this->dm->flush();
        $this->dm->clear();

        /** @var SeoAwareContent $content */
        $content = $this->dm->find(null, '/test/test-node');

        $this->assertNotNull($content);

        //test the document content
        foreach ($data as $key => $value) {
            $prop = $refl->getProperty($key);
            $prop->setAccessible(true);
            $v = $prop->getValue($content);

            if (!is_object($value)) {
                $this->assertEquals($value, $v);
            }
        }

    }
}
