<?php

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
     * @var RequestMatcherInterface[] A list of rule matchers.
     */
    private $matchersMap = array();

    /**
     * @param RequestMatcherInterface $ruleMatcher
     */
    public function addRequestMatcher(RequestMatcherInterface $ruleMatcher)
    {
        $this->matchersMap[] = $ruleMatcher;
    }

    /**
     * {@inheritDoc}
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
