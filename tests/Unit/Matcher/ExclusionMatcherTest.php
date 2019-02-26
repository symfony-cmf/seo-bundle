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

use PHPUnit\Framework\TestCase;
use Symfony\Cmf\Bundle\SeoBundle\Matcher\ExclusionMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class ExclusionMatcherTest extends TestCase
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
        $this->matcherA = $this->createMock(RequestMatcherInterface::class);
        $this->matcherB = $this->createMock(RequestMatcherInterface::class);

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
