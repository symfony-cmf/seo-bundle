<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Cmf\Bundle\SeoBundle\Matcher\ExclusionMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class ExclusionMatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RequestMatcherInterface
     */
    private $matcherB;

    /**
     * @var RequestMatcherInterface
     */
    private $matcherA;

    /**
     * @var ExclusionMatcher
     */
    private $exclusionMatcher;

    public function setUp()
    {
        $this->matcherA = $this->getMock('Symfony\Component\HttpFoundation\RequestMatcherInterface');
        $this->matcherB = $this->getMock('Symfony\Component\HttpFoundation\RequestMatcherInterface');

        $this->exclusionMatcher = new ExclusionMatcher();
        $this->exclusionMatcher->addRequestMatcher($this->matcherA);
        $this->exclusionMatcher->addRequestMatcher($this->matcherB);
    }

    public function testReturnTrueMatcherAReturnsTrue()
    {
        $this->matcherA->expects($this->once())->method('matches')->will($this->returnValue(true));

        $this->assertTrue($this->exclusionMatcher->matches(new Request()));
    }

    public function testReturnTrueMatcherBReturnsTrue()
    {
        $this->matcherA->expects($this->once())->method('matches')->will($this->returnValue(false));
        $this->matcherB->expects($this->once())->method('matches')->will($this->returnValue(true));

        $this->assertTrue($this->exclusionMatcher->matches(new Request()));
    }

    public function testReturnTrueBothReturningFalse()
    {
        $this->matcherA->expects($this->once())->method('matches')->will($this->returnValue(false));
        $this->matcherB->expects($this->once())->method('matches')->will($this->returnValue(false));

        $this->assertFalse($this->exclusionMatcher->matches(new Request()));
    }
}
