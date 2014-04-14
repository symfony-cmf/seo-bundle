<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtraPropertiesExtractor;

class ExtraPropertiesExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new ExtraPropertiesExtractor();
        $this->extractMethod = 'getSeoExtraProperties';
        $this->metadataMethod = 'setExtraProperties';
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtraPropertiesReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface'), false),
        );
    }
}
