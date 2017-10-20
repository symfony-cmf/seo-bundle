<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\GuesserChain;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderChain;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProvider;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\VoterChain;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class UrlInformationProviderTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var UrlInformationProvider
     */
    private $provider;

    public function setUp()
    {
        $accepted = new TestModel('accepted');
        $refused = new TestModel('refused');

        $loader = $this->createMock(LoaderChain::class);
        $loader
            ->expects($this->once())
            ->method('load')
            ->with('default')
            ->will(
                $this->returnValue([$accepted, $refused])
            )
        ;

        $voter = $this->createMock(VoterChain::class);
        $voter
            ->expects($this->at(0))
            ->method('exposeOnSitemap')
            ->with($accepted, 'default')
            ->will($this->returnValue(true))
        ;
        $voter
            ->expects($this->at(1))
            ->method('exposeOnSitemap')
            ->with($refused, 'default')
            ->will($this->returnValue(false))
        ;

        $guesser = $this->createMock(GuesserChain::class);
        $guesser
            ->expects($this->once())
            ->method('guessValues')
            ->with(
                $this->isInstanceOf(UrlInformation::class),
                $this->equalTo($accepted),
                $this->equalTo('default'))
            ->will($this->returnCallback(function (UrlInformation $info) {
                $info->setLocation('http://symfony.com');
            }))
        ;

        $this->provider = new UrlInformationProvider($loader, $voter, $guesser);
    }

    public function testProvider()
    {
        $urlInformations = $this->provider->getUrlInformation();
        $this->assertCount(1, $urlInformations);
        $urlInformation = reset($urlInformations);
        $this->assertInstanceof('Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation', $urlInformation);
        $this->assertEquals('http://symfony.com', $urlInformation->getLocation());
    }
}

class TestModel
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}
