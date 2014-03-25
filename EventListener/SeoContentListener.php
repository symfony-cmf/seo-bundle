<?php

namespace Symfony\Cmf\Bundle\SeoBundle\EventListener;

use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentationInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * This listener takes care on documents which implements the SeoAwareInterface.
 *
 * This interface is a sign, that the document takes care on seo. In case of a match
 * the listener calls a special presentation model to prepare the SeoMetadata for
 * putting it into sonatas Page Service.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoContentListener
{
    /**
     * @var SeoPresentationInterface
     */
    private $seoPresentation;

    /**
     * @param SeoPresentationInterface $seoPage
     */
    public function __construct(SeoPresentationInterface $seoPage)
    {
        $this->seoPresentation = $seoPage;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequest()->attributes->has(DynamicRouter::CONTENT_KEY)) {
            $this->seoPresentation->updateSeoPage($event->getRequest()->attributes->get(DynamicRouter::CONTENT_KEY));

            //have a look if the strategy is redirectResponse and if there is a route to redirectResponse to
            if ($response = $this->seoPresentation->getRedirectResponse()) {
                $event->setResponse($response);
            }
        }
    }
}
