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

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\LocationGuesser;

class LocationGuesserTest extends GuesserTestCase
{
    public function testGuessCreate()
    {
        $urlInformation = parent::testGuessCreate();
        $this->assertEquals('http://symfony.com', $urlInformation->getLocation());
    }

    /**
     * {@inheritdoc}
     */
    protected function createGuesser()
    {
        $urlGenerator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $urlGenerator
            ->expects($this->any())
            ->method('generate')
            ->with($this, array(), UrlGeneratorInterface::ABSOLUTE_URL)
            ->will($this->returnValue('http://symfony.com'))
        ;

        return new LocationGuesser($urlGenerator);
    }

    /**
     * {@inheritdoc}
     */
    protected function createData()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFields()
    {
        return array('Location');
    }
}
