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
 * This BestMatcher tries to create a route collection of
 * all ancestors of a given uri.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class AncestorBestMatcher extends PhpcrBestMatcher
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

        $manager = $this->getManagerForClass('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route');
        $parentRoute = $manager->getRepository('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route')
            ->find($parentUri);

        if (!$parentRoute) {
            return $routes;
        }

        $childRoutes = $manager->getChildren($parentRoute);
        foreach ($childRoutes->toArray() as $childRoute) {
            $routes->add($childRoute->getName(), $childRoute);
        }

        return $routes;
    }
}
