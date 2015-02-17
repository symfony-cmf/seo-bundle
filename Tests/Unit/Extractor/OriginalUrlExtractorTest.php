<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\OriginalUrlExtractor;

class OriginalUrlExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new OriginalUrlExtractor();
        $this->extractMethod = 'getSeoOriginalUrl';
        $this->metadataMethod = 'setOriginalUrl';
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\OriginalUrlReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface'), false),
        );
    }
}
