<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtrasExtractor;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtrasReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;

class ExtrasExtractorTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->extractor = new ExtrasExtractor();
    }

    public function getSupportsData()
    {
        return [
            [$this->createMock(ExtrasReadInterface::class)],
            [$this->createMock(SeoAwareInterface::class), false],
        ];
    }

    public function testExtracting()
    {
        $document = $this->getMockBuilder('ExtractedDocument')->setMethods(['getSeoExtras'])->getMock();
        $document->expects($this->any())
            ->method('getSeoExtras')
            ->will($this->returnValue([
                'property' => ['og:title' => 'Extra Title'],
                'name' => ['robots' => 'index, follow'],
                'http-equiv' => ['Content-Type' => 'text/html; charset=utf-8'],
            ]));

        $this->seoMetadata->expects($this->once())
            ->method('addExtraProperty')
            ->with($this->equalTo('og:title'), $this->equalTo('Extra Title'))
        ;

        $this->seoMetadata->expects($this->once())
            ->method('addExtraName')
            ->with($this->equalTo('robots'), $this->equalTo('index, follow'))
        ;

        $this->seoMetadata->expects($this->once())
            ->method('addExtraHttp')
            ->with($this->equalTo('Content-Type'), $this->equalTo('text/html; charset=utf-8'))
        ;

        $this->extractor->updateMetadata($document, $this->seoMetadata);
    }
}
