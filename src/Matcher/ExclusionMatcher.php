<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Matcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * Uses the native request matcher to find matching routes that had been excluded by configuration.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class ExclusionMatcher implements RequestMatcherInterface
{
    /**
     * @var RequestMatcherInterface[] a list of rule matchers
     */
    private $matchersMap = [];

    /**
     * @param RequestMatcherInterface $ruleMatcher
     */
    public function addRequestMatcher(RequestMatcherInterface $ruleMatcher)
    {
        $this->matchersMap[] = $ruleMatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(Request $request)
    {
        foreach ($this->matchersMap as $matcher) {
            if ($matcher->matches($request)) {
                return true;
            }
        }

        return false;
    }
}
