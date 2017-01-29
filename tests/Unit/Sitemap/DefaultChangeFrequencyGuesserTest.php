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

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\DefaultChangeFrequencyGuesser;

class DefaultChangeFrequencyGuesserTest extends GuesserTestCase
{
    public function testGuessCreate()
    {
        $urlInformation = parent::testGuessCreate();
        $this->assertEquals('weekly', $urlInformation->getChangeFrequency());
    }

    /**
     * {@inheritdoc}
     */
    protected function createGuesser()
    {
        return new DefaultChangeFrequencyGuesser('weekly');
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
        return ['ChangeFrequency'];
    }
}
