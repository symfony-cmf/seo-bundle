<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionExtractor;

class SeoDescriptionExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new SeoDescriptionExtractor();
        $this->extractMethod = 'getSeoDescription';
        $this->metadataMethod = 'setMetaDescription';
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoKeywordsReadInterface'), false),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteReadInterface'), false),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface'), false),
        );
    }
}
