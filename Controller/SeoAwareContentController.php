<?php

namespace Cmf\SeoBundle\Controller;

use Cmf\SeoBundle\Model\SeoAwareInterface;
use Cmf\SeoBundle\Services\CmfSeoPageInterface;
use Symfony\Cmf\Bundle\ContentBundle\Controller\ContentController;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 *
 * with the adding to serve the seo metadata to the template
 */
class SeoAwareContentController extends ContentController implements SeoAwareControllerInterface
{
    /**
     * @var CmfSeoPageInterface
     */
    protected $seoPage;

    /**
     * {@inheritDoc}
     */
    public function indexAction(Request $request, $contentDocument, $contentTemplate = null)
    {
       //when there are some seo meta data they will be handled by a special service
        if ($contentDocument instanceof SeoAwareInterface) {
            $this->seoPage->setSeoMetadata($contentDocument->getSeoMetadata());
            $this->seoPage->setMetadataValues();

            //have a look if the strategy is redirect and if there is a route to redirect to
            if ($url = $this->seoPage->getRedirect()) {
                print("should be redirected to $url");
                exit;
            }
        }

        return parent::indexAction($request, $contentDocument, $contentTemplate);
    }

    /**
     * {@inheritDoc}
     */
    public function setSeoPage(CmfSeoPageInterface $seoPage)
    {
        $this->seoPage = $seoPage;
    }
}

