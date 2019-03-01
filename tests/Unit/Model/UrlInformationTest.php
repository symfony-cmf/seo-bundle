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

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class UrlInformationTest extends TestCase
{
    /**
     * @var UrlInformation
     */
    private $model;

    public function setUp()
    {
        $this->model = new UrlInformation();
    }

    public function testSetChangeFrequencyShouldThrowExceptionForInvalidArguments()
    {
        $this->expectException(\Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid change frequency "some one", use one of always, hourly, daily, weekly, monthly, yearly, never.');

        $this->model->setChangeFrequency('some one');
    }

    public function testValidChangeFrequency()
    {
        $this->model->setChangeFrequency('never');

        $this->assertEquals('never', $this->model->getChangeFrequency());
    }
}
