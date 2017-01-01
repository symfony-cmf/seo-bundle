<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocaleCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * A SEO presentation takes a content and updates the Sonata SeoPage
 * from it.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoPresentationInterface
{
    /**
     * Updates the Sonata SeoPage service with the data retrieved from the $content.
     *
     * @param object $content the content to load data from
     */
    public function updateSeoPage($content);

    /**
     * Returns the redirect response if the bundle is configured to redirect to
     * the canonical URL and this content provided a canonical URL different
     * from the current URL. Returns false in all other cases.
     *
     * @return bool|RedirectResponse
     */
    public function getRedirectResponse();

    /**
     * Updates alternate locale information on the Sonata SeoPage service.
     *
     * @param AlternateLocaleCollection $collection
     */
    public function updateAlternateLocales(AlternateLocaleCollection $collection);
}
