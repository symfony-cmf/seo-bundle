<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\EventListener;

use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentationInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This listener takes care of content implementing the SeoAwareInterface.
 *
 * In case of a match the listener calls a special presentation model to
 * prepare the SeoMetadata for putting it into sonatas Page Service.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ContentListener
{
    /**
     * @var SeoPresentationInterface
     */
    private $seoPresentation;

    /**
     * @var string The key to look up the content in the request attributes
     */
    private $requestKey;

    /**
     * @var AlternateLocaleProviderInterface|null
     */
    private $alternateLocaleProvider;

    /**
     * @param SeoPresentationInterface $seoPage Service Handling SEO information.
     * @param string $requestKey The key to look up the content in the request attributes.
     */
    public function __construct(SeoPresentationInterface $seoPage, $requestKey)
    {
        $this->seoPresentation = $seoPage;
        $this->requestKey = $requestKey;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequest()->attributes->has($this->requestKey)) {
            $content = $event->getRequest()->attributes->get($this->requestKey);
            $this->seoPresentation->updateSeoPage($content);

            // look if the strategy is redirectResponse and if there is a route to redirectResponse to
            $response = $this->seoPresentation->getRedirectResponse();
            if (false !== $response && $this->canBeRedirected($event->getRequest(), $response)) {
                $event->setResponse($response);
            }

            if (null !== $this->alternateLocaleProvider) {
                $this->seoPresentation->updateAlternateLocales(
                    $this->alternateLocaleProvider->createForContent($content)
                );
            }
        }
    }

    protected function canBeRedirected(Request $request, RedirectResponse $response)
    {
        $targetRequest = Request::create($response->getTargetUrl());
        $stripUrl = function ($path) {
            return preg_replace('/#.+$/', '', $path);
        };
        $targetPath = $stripUrl($targetRequest->getBaseUrl().$targetRequest->getPathInfo());
        $currentPath = $stripUrl($request->getBaseUrl().$request->getPathInfo());

        return $targetPath !== $currentPath;
    }

    /**
     * @param AlternateLocaleProviderInterface $alternateLocaleProvider
     */
    public function setAlternateLocaleProvider($alternateLocaleProvider)
    {
        $this->alternateLocaleProvider = $alternateLocaleProvider;
    }
}
