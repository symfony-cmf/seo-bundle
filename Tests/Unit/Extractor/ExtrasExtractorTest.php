<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtrasExtractor;

class ExtrasExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new ExtrasExtractor();
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtrasReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface'), false),
        );
    }

    public function testExtracting()
    {
        $document = $this->getMock('ExtractedDocument', array('getSeoExtras'));
        $document->expects($this->any())
            ->method('getSeoExtras')
            ->will($this->returnValue(array(
                'property'   => array('og:title' => 'Extra Title'),
                'name'       => array('robots' => 'index, follow'),
                'http-equiv' => array('Content-Type' => 'text/html; charset=utf-8'),
            )));
        ;

        $this->seoMetadata->expects($this->once())
            ->method('addExtraProperty')
            ->with($this->equalTo('og:title'), $this->equalTo('Extra Title'))
        ;

        $this->seoMetadata->expects($this->once())
            ->method('addExtraName')
            ->with($this->equalTo('robots'), $this->equalTo('index, follow'))
        ;

        $this->seoMetadata->expects($this->once())
            ->method('addExtraHttp')
            ->with($this->equalTo('Content-Type'), $this->equalTo('text/html; charset=utf-8'))
        ;

        $this->extractor->updateMetadata($document, $this->seoMetadata);
    }
}
