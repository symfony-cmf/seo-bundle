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

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\AbstractChain;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class AbstractChainTest extends \PHPUnit_Framework_Testcase
{
    /** @var TestChain */
    private $chain;

    public function setUp()
    {
        $this->chain = new TestChain();
    }

    public function testAllChains()
    {
        $one = new TestEntry('info-one');
        $two = new TestEntry('info-two');

        $this->chain->addItem($one, 0);
        $this->chain->addItem($two, 0);

        $expectedList = array('info-one', 'info-two');

        $this->assertEquals($expectedList, $this->chain->getValues('test'));
    }

    public function testSpecificChain()
    {
        $one = new TestEntry('info-one');
        $two = new TestEntry('info-two');

        $this->chain->addItem($one, 0, 'test');
        $this->chain->addItem($two, 0, 'test');

        $expectedList = array('info-one', 'info-two');

        $this->assertEquals($expectedList, $this->chain->getValues('test'));
    }

    public function testPrioritisedInput()
    {
        $first = new TestEntry('info-first');
        $earlySpecific = new TestEntry('info-early-specific');
        $specific = new TestEntry('info-specific');
        $early = new TestEntry('info-early');
        $last = new TestEntry('info-last');

        $this->chain->addItem($early, 5);
        $this->chain->addItem($specific, 5, 'test');
        $this->chain->addItem($last, 0);
        $this->chain->addItem($first, 15);
        $this->chain->addItem($earlySpecific, 10, 'test');

        $this->assertEquals(
            array('info-first', 'info-early-specific', 'info-specific', 'info-early', 'info-last'),
            $this->chain->getValues('test')
        );

        $this->assertEquals(
            array('info-first', 'info-early', 'info-last'),
            $this->chain->getValues('foobar')
        );
    }
}

class TestChain extends AbstractChain
{
    public function getValues($sitemap)
    {
        $values = array();
        /** @var $entry TestEntry */
        foreach ($this->getSortedEntriesForSitemap($sitemap) as $entry) {
            $values[] = $entry->name;
        }

        return $values;
    }
}

class TestEntry
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}
