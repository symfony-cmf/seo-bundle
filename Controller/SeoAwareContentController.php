<?php

namespace Cmf\Bundle\SeoBundleController;

use Cmf\Bundle\SeoBundleModel\SeoAwareInterface;
use Cmf\Bundle\SeoBundleModel\SeoMetadataInterface;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\ContentBundle\Controller\ContentController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 *
 * with the adding to serve the seo metadata to the template
 */
class SeoAwareContentController extends ContentController implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function indexAction(Request $request, $contentDocument, $contentTemplate = null)
    {
       //additional check for rendering SeoMetadata
        if ($contentDocument instanceof SeoAwareInterface) {
            $this->handleMetadata($contentDocument);
        }

        return parent::indexAction($request, $contentDocument, $contentTemplate);
    }

    /**
     * Method will do the "hard" work. Means: setting the seo meta data to the SonataPage
     * service, which offers the opportunity to render it in the template
     *
     * @param SeoAwareInterface $contentDocument
     */
    protected function handleMetadata(SeoAwareInterface $contentDocument)
    {
        /** @var SeoPage $seoPage */
        $seoPage = $this->container->get('sonata.seo.page');

        /** @var SeoMetadataInterface $seoMetadata */
        $seoMetadata = $contentDocument->getSeoMetadata();

        //set the title based on the title strategy
        $title = $this->createTitle($seoMetadata, $seoPage->getTitle());
        $seoPage->setTitle($title);
        $seoPage->addMeta('property', 'title', $title);
        //todo we can add the configured description too
        $seoPage->addMeta('name', 'description', $seoMetadata->getMetaDescription());
        //todo we can add the configured keyword by ", " too
        $seoPage->addMeta('property', 'keys', $seoMetadata->getMetaKeywords());
        if ($seoMetadata->getOriginalUrlStrategy() == 'canonical') {
            $seoPage->setLinkCanonical($seoMetadata->getOriginalUrl());
        }
    }

    /**
     * based on the title strategy this method will create the title from the given
     * configs in the seo configuration part
     *
     * @param  \Cmf\Bundle\SeoBundleModel\SeoMetadata|\Cmf\Bundle\SeoBundleModel\SeoMetadataInterface $seoMetadata
     * @param  null                                                                       $configTitle
     * @return string
     */
    protected function createTitle(SeoMetadataInterface $seoMetadata, $configTitle = null)
    {
        $contentTitle = $seoMetadata->getTitle();

        switch ($seoMetadata->getTitleStrategy()) {
            case 'prepend':
                return $contentTitle. ' - '.$configTitle;
            case 'append':
                return $configTitle . ' - ' . $contentTitle;
            case 'replace':
                return $contentTitle;
            default:
                return $configTitle;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
