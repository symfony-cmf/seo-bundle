<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * A SEO presentation takes a content object and updates the Sonata SeoPage
 * from it.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoPresentationInterface
{
    /**
     * Updates the Sonata SeoPage service with the data retrieved from the $contentDocument.
     *
     * @param object $contentObject The document to load data from.
     */
    public function updateSeoPage($contentObject);

    /**
     * Returns the redirect response if the bundle is configured to redirect to
     * the canonical URL and this content provided a canonical URL different
     * from the current URL. Returns false in all other cases.
     *
     * @return boolean|RedirectResponse
     */
    public function getRedirectResponse();
}
