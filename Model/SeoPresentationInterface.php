<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * A seo presentation has to handle the main content and update the Sonata
 * SeoPage from it.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoPresentationInterface
{
    /**
     * Update the sonata SeoPage service with the data retrieved from the $contentDocument.
     *
     * @param object $contentDocument The document to load data from.
     */
    public function updateSeoPage($contentDocument);

    /**
     * Return the redirect response if the bundle is configured to redirect to
     * the canonical URL and this content provided a canonical URL different
     * from the current URL. Returns false in all other cases.
     *
     * @return bool|RedirectResponse
     */
    public function getRedirectResponse();
}
