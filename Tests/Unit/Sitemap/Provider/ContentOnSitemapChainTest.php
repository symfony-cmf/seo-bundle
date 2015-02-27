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

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider\ContentOnSitemapChain;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ContentOnSitemapChainTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var ContentOnSitemapChain
     */
    private $chainProvider;

    public function setUp()
    {
        $this->chainProvider = new ContentOnSitemapChain();
    }

    public function testInlineInput()
    {
        $providerOne = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider\ContentOnSitemapProviderInterface');
        $providerTwo = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider\ContentOnSitemapProviderInterface');

        $this->chainProvider->addItem($providerOne, 0, 'test');
        $this->chainProvider->addItem($providerTwo, 0, 'test');

        $providerOne->expects($this->once())->method('getDocumentsForSitemap')->will($this->returnValue(array('info-one')));
        $providerTwo->expects($this->once())->method('getDocumentsForSitemap')->will($this->returnValue(array('info-two')));

        $actualList = $this->chainProvider->getDocumentsForSitemap('test');
        $expectedList = array('info-one', 'info-two');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testPrioritisedInput()
    {

        $providerBeforeAll = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider\ContentOnSitemapProviderInterface');
        $providerFirst = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider\ContentOnSitemapProviderInterface');
        $providerAfterAll = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider\ContentOnSitemapProviderInterface');

        $this->chainProvider->addItem($providerFirst, 1, 'test');
        $this->chainProvider->addItem($providerBeforeAll, 0, 'test');
        $this->chainProvider->addItem($providerAfterAll, 2, 'test');

        $providerFirst
            ->expects($this->once())
            ->method('getDocumentsForSitemap')
            ->will($this->returnValue(array('info-first')));
        $providerBeforeAll
            ->expects($this->once())
            ->method('getDocumentsForSitemap')
            ->will($this->returnValue(array('info-before-all')));
        $providerAfterAll
            ->expects($this->once())
            ->method('getDocumentsForSitemap')
            ->will($this->returnValue(array('info-after-all')));

        $actualList = $this->chainProvider->getDocumentsForSitemap('test');
        $expectedList = array('info-before-all', 'info-first', 'info-after-all');

        $this->assertEquals($expectedList, $actualList);
    }
}
