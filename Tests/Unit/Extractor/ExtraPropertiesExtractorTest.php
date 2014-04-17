<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;
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
    public function testExtracting($returnValue = 'extracted', $expected = null)
    {
        $document = $this->getMock('ExtractedDocument', array('getSeoExtraProperties'));
        $document->expects($this->any())
            ->method('getSeoExtraProperties')
            ->will($this->returnValue($returnValue));
        ;

        $this->seoMetadata->expects($this->once())
            ->method('setExtraProperties')
            ->with($this->equalTo($expected === null ? $returnValue : $expected))
        ;

        $this->extractor->updateMetadata($document, $this->seoMetadata);
    }

    public function getExtractingData()
    {
        return array(
            array(
                array(
                    new ExtraProperty('og:title', 'Hello', 'property'),
                    new ExtraProperty('og:description', 'lorem ipsum', 'property')
                ),
                new ArrayCollection(array(
                    new ExtraProperty('og:title', 'Hello', 'property'),
                    new ExtraProperty('og:description', 'lorem ipsum', 'property')
                )),
            ),
            array(
                new ArrayCollection(array(
                    new ExtraProperty('og:title', 'Hello', 'property'),
                    new ExtraProperty('og:description', 'lorem ipsum', 'property')
                )),
            ),
        );
    }
}
