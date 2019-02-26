<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\ConfigValues;

class ConfigValuesTest extends TestCase
{
    public function testInvalidStrategy()
    {
        $this->expectException(\Symfony\Cmf\Bundle\SeoBundle\Exception\ExtractorStrategyException::class);

        $configValues = new ConfigValues();
        $configValues->setOriginalUrlBehaviour('nonexistent');
    }
}
