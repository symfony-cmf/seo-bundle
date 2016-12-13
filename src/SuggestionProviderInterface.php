<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

/**
 * Interface for all suggestion providers.
 *
 * Those providers are responsible to create a list of suggestions (routes)
 * for pages to visit instead of a blank 404 page.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SuggestionProviderInterface
{
    /**
     * Based on the current request this method creates a list
     * of suggestions as an array of routes.
     *
     * @param Request $request
     *
     * @return array|Route[]
     */
    public function create(Request $request);
}
