<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\ConfigValues;

class ConfigValuesTest extends TestCase
{
    /**
     * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exception\ExtractorStrategyException
     */
    public function testInvalidStrategy()
    {
        $configValues = new ConfigValues();
        $configValues->setOriginalUrlBehaviour('nonexistent');
    }
}
