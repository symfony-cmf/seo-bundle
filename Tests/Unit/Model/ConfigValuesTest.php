<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\ConfigValues;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\AllStrategiesDocument;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\DescriptionExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\OriginalUrlExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\TitleExtractor;
use Symfony\Cmf\Bundle\SeoBundle\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigValuesTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exception\ExtractorStrategyException
     */
    public function testInvalidStrategy()
    {
        $configValues = new ConfigValues();
        $configValues->setOriginalUrlBehaviour('nonexistent');
    }
}
