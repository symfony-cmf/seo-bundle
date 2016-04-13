<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\TitleExtractor;

class TitleExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new TitleExtractor();
        $this->extractMethod = 'getSeoTitle';
        $this->metadataMethod = 'setTitle';
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\TitleReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface'), false),
        );
    }
}
