<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use PHPCR\Util\PathHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

/**
 * This provider checks if the parent of a requested URL exists and proposes
 * other children of that parent.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SiblingSuggestionProvider extends BaseSuggestionProvider
{
    /**
     * {@inheritdoc}
     */
    public function create(Request $request)
    {
        $manager = $this->getManagerForClass('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route');
        $parentPath = PathHelper::getParentPath($this->routeBasePath.$request->getPathInfo());
        $parentRoute = $manager->find(null, $parentPath);

        if (!$parentRoute) {
            return array();
        }

        $routes = array();
        $childRoutes = $manager->getChildren($parentRoute);

        foreach ($childRoutes->toArray() as $childRoute) {
            if ($childRoute instanceof Route) {
                $routes[$childRoute->getName()] = $childRoute;
            }
        }

        return $routes;
    }
}
