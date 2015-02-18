<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Interface for Guesser, that set values on UrlInformation.
 *
 * Each guesser should only update values that are not already set, to play
 * nicely with other guessers.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface GuesserInterface
{
    /**
     * Updates UrlInformation with new values if they are not already set.
     *
     * @param UrlInformation $urlInformation
     * @param object         $object
     * @param string         $sitemap
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap);
}
