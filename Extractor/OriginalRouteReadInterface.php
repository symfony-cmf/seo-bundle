<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Component\Routing\Route;

/**
 * This interface is one of the ExtractorInterfaces to
 * get content properties for updating the SeoMetadata.
 *
 * If you want to have a content that is able to update its
 * original route for the SeoMetadata on its own, you should implement
 * this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface OriginalRouteReadInterface
{
    /**
     * Returns something that can be used to generate an absolute URL.
     *
     * This may be a Symfony route name or - when using the Symfony CMF
     * DynamicRouter - a Route object.
     *
     * @return Route|string
     */
    public function getSeoOriginalRoute();
}
