<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\ErrorHandling;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

/**
 * This BestMatcher will try to detect a parent route of a given uri.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ParentBestMatcher extends PhpcrBestMatcher
{
    /**
     * {@inheritDoc}
     */
    public function create(Request $request)
    {
        $routes = new RouteCollection();
        $uriAsArray = explode('/', $request->getUri());
        if (count($uriAsArray) <= 1) {
            return $routes;
        }

        $uriAsArray = array_shift($uriAsArray);
        $parentUri = implode('/', $uriAsArray);

    }
}
