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

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class SeoMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSeoAwareWithoutCurrentMetadata()
    {
        $content = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent');
        $content
            ->expects($this->any())
            ->method('getSeoMetadata')
            ->will($this->returnValue(null))
        ;

        $content
            ->expects($this->once())
            ->method('setSeoMetadata')
            ->with($this->isInstanceOf(SeoMetadataInterface::class))
        ;

        SeoMetadataFactory::initializeSeoMetadata($content);
    }
}
