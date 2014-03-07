<?php

namespace Symfony\Cmf\Bundle\SeoBundle\EventListener;

use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentationInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    private $seoPage;

    /**
     * @param SeoPresentationInterface $seoPage
     */
    public function __construct(SeoPresentationInterface $seoPage)
    {
        $this->seoPage = $seoPage;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $contentDocument = $event->getRequest()->attributes->get(DynamicRouter::CONTENT_KEY);
        if ($contentDocument instanceof SeoAwareInterface) {
            $this->seoPage->setContentDocument($contentDocument);
            $this->seoPage->setMetadataValues();

            //have a look if the strategy is redirect and if there is a route to redirect to
            if ($response = $this->seoPage->getRedirect()) {
                $event->setResponse(new RedirectResponse($response));
            }
        }
    }
}
