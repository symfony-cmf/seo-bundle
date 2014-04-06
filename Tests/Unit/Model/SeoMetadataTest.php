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
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;

/**
 * @author Maximilian Berghoff <maximilian.berghoff@gmx.de>
 */
class SeoMetadataTest extends \PHPUnit_Framework_TestCase {


    public function testNullValues()
    {
        $seoMetadata = new SeoMetadata();

        $actual = $seoMetadata->toArray();
        $expected = array(
            'title'                 => '',
            'metaDescription'       => '',
            'metaKeywords'          => '',
            'originalUrl'           => '',
        );

        $this->assertEquals($expected, $actual);
    }
}
 