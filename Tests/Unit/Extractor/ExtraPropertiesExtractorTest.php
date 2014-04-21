<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtraPropertiesExtractor;

class ExtraPropertiesExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new ExtraPropertiesExtractor();
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtraPropertiesReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface'), false),
        );
    }

    /**
     * @dataProvider getExtractingData
     */
    public function testExtracting()
    {
        $document = $this->getMock('ExtractedDocument', array('getSeoExtraProperties', 'getSeoExtraNames', 'getSeoExtraHttp'));
        $document->expects($this->any())
            ->method('getSeoExtraProperties')
            ->will($this->returnValue(array('og:title' => 'Extra Title')));
        ;

        $document->expects($this->any())
            ->method('getSeoExtraNames')
            ->will($this->returnValue(array('robots' => 'index, follow')));
        ;

        $document->expects($this->any())
            ->method('getSeoExtraHttp')
            ->will($this->returnValue(array('Content-Type' => 'text/html; charset=utf-8')));
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

    public function getExtractingData()
    {
        return array(
                array('og:title', 'Hello', 'property'),
                array('og:description', 'lorem ipsum', 'property'),
                array('og:title', 'Hello', 'property'),
                array('og:description', 'lorem ipsum', 'property'),
                array('og:title', 'Hello', 'property'),
                array('og:description', 'lorem ipsum', 'property'),
        );
    }
}
