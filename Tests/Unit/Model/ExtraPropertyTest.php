<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;

use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;

class ExtraPropertyTest extends \PHPUnit_Framework_TestCase {

    /**
     * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException
     */
    public function testExceptionForNotAllowedType()
    {
        new ExtraProperty('test', 'test', 'test');
    }
}
