<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\SeoMetadataTitleGuesser;

class SeoMetadataTitleGuesserTest extends GuesserTestCase
{
    public function testGuessCreate()
    {
        $urlInformation = parent::testGuessCreate();
        $this->assertEquals('Symfony CMF', $urlInformation->getLabel());
    }

    /**
     * @inheritdoc
     */
    protected function createGuesser()
    {
        $seoMetadata = new SeoMetadata();
        $seoMetadata->setTitle('Symfony CMF');
        $seoPresentation = $this->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\SeoPresentation')->disableOriginalConstructor()->getMock();
        $seoPresentation
            ->expects($this->any())
            ->method('getSeoMetadata')
            ->with($this)
            ->will($this->returnValue($seoMetadata))
        ;

        return new SeoMetadataTitleGuesser($seoPresentation);
    }

    /**
     * @inheritdoc
     */
    protected function createData()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function getFields()
    {
        return array('Label');
    }
}
