<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoConfigValues;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\AllStrategiesDocument;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentation;
use Symfony\Component\Translation\TranslatorInterface;

class SeoConfigValuesTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exception\SeoExtractorStrategyException
     */
    public function testInvalidStrategy()
    {
        $configValues = new SeoConfigValues();
        $configValues->setOriginalUrlBehaviour('nonexistent');
    }
}
