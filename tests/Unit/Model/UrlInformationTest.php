<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class UrlInformationTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var UrlInformation
     */
    private $model;

    public function setUp()
    {
        $this->model = new UrlInformation();
    }

    /**
     * @expectedException \Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid change frequency "some one", use one of always, hourly, daily, weekly, monthly, yearly, never.
     */
    public function testSetChangeFrequencyShouldThrowExceptionForInvalidArguments()
    {
        $this->model->setChangeFrequency('some one');
    }

    public function testValidChangeFrequency()
    {
        $this->model->setChangeFrequency('never');

        $this->assertEquals('never', $this->model->getChangeFrequency());
    }
}
