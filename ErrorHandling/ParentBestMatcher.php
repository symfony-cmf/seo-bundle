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

use Doctrine\Common\Persistence\ManagerRegistry;
use PHPCR\Util\PathHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
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
        $routes = array();
        $manager = $this->getManagerForClass('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route');
        $parentPath = PathHelper::getParentPath($this->routeBasePath.$request->getPathInfo());

        $parentRoute = $manager->find(null, $parentPath);
        if (!$parentRoute) {
            return $routes;
        }

        if ($parentRoute instanceof \Symfony\Component\Routing\Route) {
            $routes[$parentRoute->getName()] = $parentRoute;
        }

        return $routes;
    }
}
