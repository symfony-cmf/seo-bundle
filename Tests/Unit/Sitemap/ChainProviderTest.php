<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\ChainProvider;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ChainProviderTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var ChainProvider
     */
    private $chainProvider;

    public function setUp()
    {
        $this->chainProvider = new ChainProvider();
    }

    public function testInlineInput()
    {
        $providerOne = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface');
        $providerTwo = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface');

        $this->chainProvider->addProvider($providerOne);
        $this->chainProvider->addProvider($providerTwo);

        $providerOne->expects($this->once())->method('getUrlInformation')->will($this->returnValue(array('info-one')));
        $providerTwo->expects($this->once())->method('getUrlInformation')->will($this->returnValue(array('info-two')));

        $actualList = $this->chainProvider->getUrlInformation();
        $expectedList = array('info-one', 'info-two');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testPrioritisedInput()
    {

        $providerBeforeAll = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface');
        $providerFirst = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface');
        $providerAfterAll = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface');

        $this->chainProvider->addProvider($providerFirst, 1);
        $this->chainProvider->addProvider($providerBeforeAll, 0);
        $this->chainProvider->addProvider($providerAfterAll, 2);

        $providerFirst
            ->expects($this->once())
            ->method('getUrlInformation')
            ->will($this->returnValue(array('info-first')));
        $providerBeforeAll
            ->expects($this->once())
            ->method('getUrlInformation')
            ->will($this->returnValue(array('info-before-all')));
        $providerAfterAll
            ->expects($this->once())
            ->method('getUrlInformation')
            ->will($this->returnValue(array('info-after-all')));

        $actualList = $this->chainProvider->getUrlInformation();
        $expectedList = array('info-before-all', 'info-first', 'info-after-all');

        $this->assertEquals($expectedList, $actualList);
    }
}
