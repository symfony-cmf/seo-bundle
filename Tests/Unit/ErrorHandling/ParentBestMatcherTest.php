<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\ErrorHandling;

use Symfony\Cmf\Bundle\SeoBundle\ErrorHandling\ParentBestMatcher;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ParentBestMatcherTest extends \PHPUnit_Framework_TestCase
{
    private $matcher;

    public function setUp()
    {
        $this->matcher = new ParentBestMatcher();
    }
}
