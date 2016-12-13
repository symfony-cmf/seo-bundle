<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\SitemapAwareDocumentVoter;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\VoterInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class SitemapAwareDocumentVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VoterInterface
     */
    protected $voter;
    protected $sitemapAwareDocument;

    public function setUp()
    {
        $this->voter = new SitemapAwareDocumentVoter();
        $this->sitemapAwareDocument = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\SitemapAwareInterface');
    }

    public function testSitemapAwareDocumentShouldReturnTrueWhenDocumentIsExposed()
    {
        $this->sitemapAwareDocument
            ->expects($this->once())
            ->method('isVisibleInSitemap')
            ->will($this->returnValue(true));
        $this->assertTrue($this->voter->exposeOnSitemap($this->sitemapAwareDocument, 'some-sitemap'));
    }

    public function testSitemapAwareDocumentShouldReturnFalseWhenDocumentIsNotExposed()
    {
        $this->sitemapAwareDocument
            ->expects($this->once())
            ->method('isVisibleInSitemap')
            ->will($this->returnValue(false));
        $this->assertFalse($this->voter->exposeOnSitemap($this->sitemapAwareDocument, 'some-sitemap'));
    }

    public function testInvalidDocumentShouldReturnTrueToBeAwareForTheOtherVoters()
    {
        $this->assertTrue($this->voter->exposeOnSitemap(new \stdClass(), 'some-sitemap'));
    }
}
