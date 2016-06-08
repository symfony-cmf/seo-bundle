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
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\GuesserInterface;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\LastModifiedGuesser;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class LastModifiedGuesserTest extends GuesserTestCase
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var DocumentManager
     */
    private $manager;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    public function testGuessCreate()
    {
        $urlInformation = parent::testGuessCreate();
        $this->assertEquals('2016-07-06T00:00:00+02:00', $urlInformation->getLastModification());
    }

    /**
     * Create the guesser for this test.
     *
     * @return GuesserInterface
     */
    protected function createGuesser()
    {
        $this->registry = $this->getMockBuilder('\Doctrine\Bundle\PHPCRBundle\ManagerRegistry')->disableOriginalConstructor()->getMock();
        $this->manager = $this->getMockBuilder('\Doctrine\ODM\PHPCR\DocumentManager')->disableOriginalConstructor()->getMock();
        $this->metadata = $this->getMockBuilder('\Doctrine\ODM\PHPCR\Mapping\ClassMetadata')->disableOriginalConstructor()->getMock();
        $this->registry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with($this->equalTo('Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap\LastModifiedGuesserTest'))
            ->will($this->returnValue($this->manager));
        $this->manager
            ->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo('Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap\LastModifiedGuesserTest'))
            ->will($this->returnValue($this->metadata));
        $this->metadata
            ->expects($this->any())
            ->method('getMixins')
            ->will($this->returnValue(array('mix:lastModified')));
        $this->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(array('lastModified')));
        $this->metadata
            ->expects($this->any())
            ->method('getField')
            ->with($this->equalTo('lastModified'))
            ->will($this->returnValue(array('property' => 'jcr:lastModified')));
        $this->metadata
            ->expects($this->any())
            ->method('getFieldValue')
            ->with($this->equalTo($this), $this->equalTo('lastModified'))
            ->will($this->returnValue(new \DateTime('2016-07-06', new \DateTimeZone('Europe/Berlin'))));

        return new LastModifiedGuesser($this->registry);
    }

    /**
     * @return object
     */
    protected function createData()
    {
        return $this;
    }

    /**
     * Provide list of fields in UrlInformation covered by this guesser.
     *
     * @return array
     */
    protected function getFields()
    {
        return array('LastModification');
    }

    public function testGuessNoOverwrite()
    {
        $urlInformation = new UrlInformation();
        $urlInformation->setLastModification(new \DateTime('2016-06-06', new \DateTimeZone('Europe/Berlin')));

        $this->guesser->guessValues($urlInformation, $this->data, 'default');
        $this->assertEquals('2016-06-06T00:00:00+02:00', $urlInformation->getLastModification());
    }
}
