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

use Symfony\Cmf\Bundle\SeoBundle\Extractor\KeywordsExtractor;

class KeywordsExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new KeywordsExtractor();
        $this->extractMethod = 'getSeoKeywords';
        $this->metadataMethod = 'setMetaKeywords';
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\KeywordsReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface'), false),
        );
    }
}
