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

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected $seoMetadata;

    // the properties below has to be configured by the child class
    protected $extractor;
    protected $extractMethod;
    protected $metadataMethod;

    public function setUp()
    {
        $this->seoMetadata = $this->createMock(SeoMetadataInterface::class);
    }

    /**
     * @dataProvider getSupportsData
     */
    public function testSupports($object, $supports = true)
    {
        $result = $this->extractor->supports($object);

        if ($supports) {
            $this->assertTrue($result);
        } else {
            $this->assertFalse($result);
        }
    }

    abstract public function getSupportsData();

    public function testExtracting()
    {
        $document = $this->getMockBuilder('ExtractedDocument')->setMethods([$this->extractMethod])->getMock();
        $document->expects($this->any())
            ->method($this->extractMethod)
            ->will($this->returnValue('extracted'));

        $this->seoMetadata->expects($this->once())
            ->method($this->metadataMethod)
            ->with($this->equalTo('extracted'))
        ;

        $this->extractor->updateMetadata($document, $this->seoMetadata);
    }
}
