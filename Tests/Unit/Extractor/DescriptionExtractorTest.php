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

use Symfony\Cmf\Bundle\SeoBundle\Extractor\DescriptionExtractor;

class DescriptionExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new DescriptionExtractor();
        $this->extractMethod = 'getSeoDescription';
        $this->metadataMethod = 'setMetaDescription';
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\DescriptionReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface'), false),
        );
    }
}
