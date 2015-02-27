<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap\Provider;

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\Loader;


/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ContentOnSitemapChainTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var Loader
     */
    private $chainProvider;

    public function setUp()
    {
        $this->chainProvider = new Loader();
    }

    public function testInlineInput()
    {
        $providerOne = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface');
        $providerTwo = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface');

        $this->chainProvider->addItem($providerOne, 0, 'test');
        $this->chainProvider->addItem($providerTwo, 0, 'test');

        $providerOne->expects($this->once())->method('load')->will($this->returnValue(array('info-one')));
        $providerTwo->expects($this->once())->method('load')->will($this->returnValue(array('info-two')));

        $actualList = $this->chainProvider->load('test');
        $expectedList = array('info-one', 'info-two');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testPrioritisedInput()
    {

        $providerBeforeAll = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface');
        $providerFirst = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface');
        $providerAfterAll = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface');

        $this->chainProvider->addItem($providerFirst, 1, 'test');
        $this->chainProvider->addItem($providerBeforeAll, 0, 'test');
        $this->chainProvider->addItem($providerAfterAll, 2, 'test');

        $providerFirst
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array('info-first')));
        $providerBeforeAll
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array('info-before-all')));
        $providerAfterAll
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array('info-after-all')));

        $actualList = $this->chainProvider->load('test');
        $expectedList = array('info-before-all', 'info-first', 'info-after-all');

        $this->assertEquals($expectedList, $actualList);
    }
}
