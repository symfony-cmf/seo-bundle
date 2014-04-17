<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteExtractor;

class SeoOriginalRouteExtractorTest extends BaseTestCase
{
    protected $urlGenerator;

    public function setUp()
    {
        parent::setUp();

        $this->urlGenerator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $this->extractor = new SeoOriginalRouteExtractor($this->urlGenerator);
        $this->extractMethod = 'getSeoOriginalRoute';
        $this->metadataMethod = 'setOriginalUrl';
    }

    public function getSupportsData()
    {
        return array(
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteReadInterface')),
            array($this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface'), false),
        );
    }

    public function testExtracting()
    {
        $this->urlGenerator->expects($this->any())
            ->method('generate')
            ->with($this->equalTo('extracted'))
            ->will($this->returnValue('extracted'))
        ;

        parent::testExtracting();
    }
}
