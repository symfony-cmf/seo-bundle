<?php

namespace Symfony\Cmf\Bundle\SeoBundle\EventListener;

use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentationInterface;
use Symfony\Cmf\Bundle\SeoBundle\Services\TitleParametersLocaleMatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class SeoContentListener
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
    public function onKernelRequest(GetResponseEvent  $event)
    {
        $contentDocument = $event->getRequest()->attributes->get(DynamicRouter::CONTENT_KEY);
        if ($contentDocument instanceof SeoAwareInterface) {
            $this->seoPage->setSeoMetadata($contentDocument->getSeoMetadata());
            $this->seoPage->setMetadataValues();

            //have a look if the strategy is redirect and if there is a route to redirect to
            if ($url = $this->seoPage->getRedirect()) {
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }
}