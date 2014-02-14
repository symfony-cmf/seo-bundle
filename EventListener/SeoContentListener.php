<?php

namespace Symfony\Cmf\Bundle\SeoBundle\EventListener;

use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent;
use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentationInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class SeoContentListener
{
    /**
     * @var SeoPresentationInterface
     */
    private $seoPage;


    public function __construct(SeoPresentationInterface $seoPage)
    {
        $this->seoPage = $seoPage;
    }

    public function onControllerFound(FilterControllerEvent  $event)
    {
        $contentDocument = $event->getRequest()->attributes->get(DynamicRouter::CONTENT_KEY);
        if ($contentDocument instanceof SeoAwareContent) {
            $this->seoPage->setSeoMetadata($contentDocument->getSeoMetadata());
            $this->seoPage->setMetadataValues();

            //have a look if the strategy is redirect and if there is a route to redirect to
            if ($url = $this->seoPage->getRedirect()) {
                print("should be redirected to $url");
                exit;
            }
        }
    }
}