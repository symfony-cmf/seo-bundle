<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Component\Routing\Route;

/**
 * Providers are able to create a list of routes for the sitemap creation.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface LoaderInterface
{
    /**
     * @param string $sitemap the name of the sitemap
     *
     * @return Route[]
     */
    public function load($sitemap);
}
