<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Model\Extra;
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
        $document = $this->getMock('ExtractedDocument', array('getSeoExtraProperties'));
        $document->expects($this->any())
            ->method('getSeoExtraProperties')
            ->will($this->returnValue(array('og:title' => 'Extra Title')));
        ;

        $this->seoMetadata->expects($this->once())
            ->method('setExtraProperties')
            ->with($this->equalTo(array('og:title' => 'Extra Title')))
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
