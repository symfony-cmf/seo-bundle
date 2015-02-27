<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\AbstractChain;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
abstract class AbstractChainTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var AbstractChain
     */
    protected $chain;
    protected $interface;
    protected $methodName;
    protected $parameter;

    public function setUp()
    {
        $this->chain = $this->getChain();
        $this->interface = $this->getInterface();
        $this->methodName = $this->getMethodName();
        $this->parameter = $this->getParameter();
    }

    abstract protected function getChain();
    abstract protected function getInterface();
    abstract protected function getMethodName();
    abstract protected function getParameter();

    public function testInlineInput()
    {
        $one = $this->getMock($this->interface);
        $two = $this->getMock($this->interface);

        $this->chain->addItem($one, 0, 'test');
        $this->chain->addItem($two, 0, 'test');

        $one->expects($this->once())->method($this->methodName)->will($this->returnValue(array('info-one')));
        $two->expects($this->once())->method($this->methodName)->will($this->returnValue(array('info-two')));

        $actualList = array();
        if (count($this->parameter) === 1) {
            $actualList = $this->chain->{$this->methodName}($this->parameter[0]);
        } elseif (count($this->parameter) === 2) {
            $actualList = $this->chain->{$this->methodName}($this->parameter[0], $this->parameter[1]);
        } elseif (count($this->parameter) === 3) {
            $actualList = $this->chain->{$this->methodName}(
                $this->parameter[0],
                $this->parameter[1],
                $this->parameter[2]
            );
        }

        $expectedList = array('info-one', 'info-two');

        $this->assertEquals($expectedList, $actualList);
    }

    public function testPrioritisedInput()
    {

        $beforeAll = $this->getMock($this->interface);
        $first = $this->getMock($this->interface);
        $afterAll = $this->getMock($this->interface);

        $this->chain->addItem($first, 1, 'test');
        $this->chain->addItem($beforeAll, 0, 'test');
        $this->chain->addItem($afterAll, 2, 'test');

        $first
            ->expects($this->once())
            ->method($this->methodName)
            ->will($this->returnValue(array('info-first')));
        $beforeAll
            ->expects($this->once())
            ->method($this->methodName)
            ->will($this->returnValue(array('info-before-all')));
        $afterAll
            ->expects($this->once())
            ->method($this->methodName)
            ->will($this->returnValue(array('info-after-all')));

        $actualList = array();
        if (count($this->parameter) === 1) {
            $actualList = $this->chain->{$this->methodName}($this->parameter[0]);
        } elseif (count($this->parameter) === 2) {
            $actualList = $this->chain->{$this->methodName}($this->parameter[0], $this->parameter[1]);
        } elseif (count($this->parameter) === 3) {
            $actualList = $this->chain->{$this->methodName}(
                $this->parameter[0],
                $this->parameter[1],
                $this->parameter[2]
            );
        }

        $expectedList = array('info-before-all', 'info-first', 'info-after-all');

        $this->assertEquals($expectedList, $actualList);
    }
}
