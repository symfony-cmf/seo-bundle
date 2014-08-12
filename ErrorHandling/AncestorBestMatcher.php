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

use PHPCR\Util\PathHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
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
        $manager = $this->getManagerForClass('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route');
        $parentPath = PathHelper::getParentPath($this->routeBasePath.'/'.$request->getUri());

        $parentRoute = $manager->find('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route', $parentPath);
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
