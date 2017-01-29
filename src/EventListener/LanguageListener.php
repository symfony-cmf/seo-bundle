<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This listener adds a Content-Language header to the response.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class LanguageListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getResponse()->headers->has('Content-Language')) {
            return;
        }

        $locale = $event->getRequest()->getLocale();
        $language = current(explode('_', $locale, 2));
        $event->getResponse()->headers->set('Content-Language', $language);
    }
}
