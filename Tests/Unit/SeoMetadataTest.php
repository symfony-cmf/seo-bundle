<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;
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
            'title'           => '',
            'metaDescription' => '',
            'metaKeywords'    => '',
            'originalUrl'     => '',
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider getExtraProperties
     */
    public function testExtraProperties($expectedType, $expectedKey, $expectedValue)
    {
        $metadata = new SeoMetadata();
        $metadata->addExtraProperty(new ExtraProperty($expectedKey, $expectedValue, $expectedType));

        $expected = array(
            $expectedType.'_'.$expectedKey => $expectedValue,
            'title'           => '',
            'metaDescription' => '',
            'metaKeywords'    => '',
            'originalUrl'     => '',
        );

        $this->assertEquals($expected, $metadata->toArray());

        $expectedCollection = new ArrayCollection();
        $expectedCollection->add(new ExtraProperty($expectedKey, $expectedValue, $expectedType));

        $this->assertEquals($expectedCollection, $metadata::createFromArray($expected)->getExtraProperties());
    }

    public function getExtraProperties()
    {
        return array(
            array('property', 'og:title', 'extra title'),
            array('name', 'robots', 'index, follow'),
            array('http-equiv', 'Content-Type', 'text/html; charset=utf-8'),
        );
    }
}
