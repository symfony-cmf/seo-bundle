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

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\NodeInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\DepthGuesser;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\GuesserInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class DepthGuesserTest extends GuesserTestCase
{
    /**
     * @var \stdClass
     */
    protected $object;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var DepthGuesser
     */
    protected $guesser;

    /**
     * @var NodeInterface
     */
    protected $node;

    /**
     * Create the guesser for this test.
     *
     * @return GuesserInterface
     */
    protected function createGuesser()
    {
        $this->buildMocks();

        $this->managerRegistry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with(get_class($this->object))
            ->will($this->returnValue($this->documentManager));
        $this->documentManager
            ->expects($this->any())
            ->method('getNodeForDocument')
            ->with($this->object)
            ->willReturn($this->node);
        $this->node
            ->expects($this->any())
            ->method('getDepth')
            ->will($this->returnValue(3));

        return $this->guesser;
    }

    /**
     * @return object
     */
    protected function createData()
    {
        return $this->object;
    }

    /**
     * Provide list of fields in UrlInformation covered by this guesser.
     *
     * @return array
     */
    protected function getFields()
    {
        return array('depth');
    }

    /**
     * Method to extract mock building.
     *
     * @param string $contentBasePath
     */
    private function buildMocks($contentBasePath = '/cms/test')
    {
        $this->managerRegistry = $this->getMockBuilder('Doctrine\Bundle\PHPCRBundle\ManagerRegistry')
            ->disableOriginalConstructor()
            ->getMock();
        $this->documentManager = $this->getMockBuilder('Doctrine\ODM\PHPCR\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->node = $this->getMock('PHPCR\NodeInterface');
        $this->object = new \stdClass();
        $this->guesser = new DepthGuesser($this->managerRegistry, $contentBasePath);
    }

    public function testNullOnNoManager()
    {
        $this->buildMocks();
        $this->managerRegistry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with(get_class($this->object))
            ->will($this->returnValue(null));
        $urlInformation = new UrlInformation();
        $this->guesser->guessValues($urlInformation, $this->object, 'default');

        $this->assertNull($urlInformation->getDepth());
    }

    public function testDepthOffsetCalculation()
    {
        $this->buildMocks();

        $this->managerRegistry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with(get_class($this->object))
            ->will($this->returnValue($this->documentManager));
        $this->documentManager
            ->expects($this->any())
            ->method('getNodeForDocument')
            ->with($this->object)
            ->willReturn($this->node);
        $this->node
            ->expects($this->any())
            ->method('getDepth')
            ->will($this->returnValue(4));
        $urlInformation = new UrlInformation();
        $this->guesser->guessValues($urlInformation, $this->object, 'default');

        $this->assertEquals(1, $urlInformation->getDepth());
    }

    public function testRootEdgeCase()
    {
        $this->buildMocks('/');

        $this->managerRegistry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with(get_class($this->object))
            ->will($this->returnValue($this->documentManager));
        $this->documentManager
            ->expects($this->any())
            ->method('getNodeForDocument')
            ->with($this->object)
            ->willReturn($this->node);
        $this->node
            ->expects($this->any())
            ->method('getDepth')
            ->will($this->returnValue(4));
        $urlInformation = new UrlInformation();
        $this->guesser->guessValues($urlInformation, $this->object, 'default');

        $this->assertEquals(3, $urlInformation->getDepth());
    }
}
