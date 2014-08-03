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
        // TODO: Implement create() method.
    }
}
