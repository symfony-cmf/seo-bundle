<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

/**
 * This provider looks for the direct parent of the requested URL.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ParentSuggestionProvider extends BaseSuggestionProvider
{
    /**
     * {@inheritdoc}
     */
    public function create(Request $request)
    {
        $routes = [];
        $parentRoute = $this->findParentRoute($request->getPathInfo());

        if (null === $parentRoute) {
            return [];
        }

        if ($parentRoute instanceof Route) {
            $routes[$parentRoute->getName()] = $parentRoute;
        }

        return $routes;
    }
}
