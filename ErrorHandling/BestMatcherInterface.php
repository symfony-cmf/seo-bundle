<?php


namespace Symfony\Cmf\Bundle\SeoBundle\ErrorHandling;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

/**
 * Interface to create a BestMatcher.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface BestMatcherInterface
{
    /**
     * Based on the current request BestMatcher creates collections of
     * route as suggestions to navigate to.
     *
     * @param Request $request
     * @return RouteCollection
     */
    public function create(Request $request);
}
