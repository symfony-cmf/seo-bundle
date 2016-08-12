<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Loader;

use Symfony\Cmf\Bundle\SeoBundle\Loader\SeoMetadataFactory;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Prophecy\Argument;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class SeoMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSeoAwareWithoutCurrentMetadata()
    {
        $content = $this->prophesize(SeoAwareInterface::class);
        $content->getSeoMetadata()->willReturn(null);

        $content->setSeoMetadata(Argument::type(SeoMetadataInterface::class))->shouldBeCalled();

        SeoMetadataFactory::initializeSeoMetadata($content->reveal());
    }

    public function testSeoAwareWithCurrentMetadata()
    {
        $seoMetadata = $this->prophesize(SeoMetadataInterface::class);
        $seoMetadata->getTitle()->willReturn('Test');
        $seoMetadata->getMetaKeywords()->willReturn('test1, test2');
        $seoMetadata->getMetaDescription()->willReturn('Some copy test');
        $seoMetadata->getOriginalUrl()->willReturn('http://example.org/test');
        $seoMetadata->getExtraProperties()->willReturn(['og:title' => 'Test Extra']);
        $seoMetadata->getExtraNames()->willReturn(['robots' => 'index, follow']);
        $seoMetadata->getExtraHttp()->willReturn(['Content-Type' => 'text/html']);

        $content = $this->prophesize(SeoAwareInterface::class);
        $content->getSeoMetadata()->willReturn($seoMetadata->reveal());

        $returnedSeoMetadata = SeoMetadataFactory::initializeSeoMetadata($content->reveal());

        $this->assertEquals('Test', $returnedSeoMetadata->getTitle());
        $this->assertEquals('test1, test2', $returnedSeoMetadata->getMetaKeywords());
        $this->assertEquals('Some copy test', $returnedSeoMetadata->getMetaDescription());
        $this->assertEquals('http://example.org/test', $returnedSeoMetadata->getOriginalUrl());
        $this->assertEquals(['og:title' => 'Test Extra'], $returnedSeoMetadata->getExtraProperties());
        $this->assertEquals(['robots' => 'index, follow'], $returnedSeoMetadata->getExtraNames());
        $this->assertEquals(['Content-Type' => 'text/html'], $returnedSeoMetadata->getExtraHttp());
    }
}
