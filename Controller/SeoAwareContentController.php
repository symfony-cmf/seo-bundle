<?php

namespace Cmf\SeoBundle\Controller;

use Cmf\SeoBundle\Model\SeoAwareInterface;
use Cmf\SeoBundle\Model\SeoMetadataInterface;
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

        if ($this->container->getParameter('cmf_seo.title')) {
            $title = $this->createTitle($seoMetadata);
            $seoPage->setTitle($title);
            $seoPage->addMeta('property', 'title', $title);
        }


        $seoPage->addMeta(
            'name',
            'description',
            $seoMetadata->getMetaDescription() . ' '.$this->container->getParameter('cmf_seo.description')
        );

        $seoPage->addMeta(
            'property',
            'keywords',
            $seoMetadata->getMetaKeywords() . ', '.$this->container->getParameter('cmf_seo.keys')
        );
        if ($this->container->getParameter('cmf_seo.content.strategy') == 'canonical') {
            $seoPage->setLinkCanonical($seoMetadata->getOriginalUrl());
        }

        //todo do a redirect else, or let it be a redirect doc
    }

    /**
     * based on the title strategy this method will create the title from the given
     * configs in the seo configuration part
     *
     * @param  \Cmf\SeoBundle\Model\SeoMetadata|\Cmf\SeoBundle\Model\SeoMetadataInterface $seoMetadata
     * @return string
     */
    protected function createTitle(SeoMetadataInterface $seoMetadata)
    {
        $contentTitle = $seoMetadata->getTitle();
        $defaultTitle = $this->container->getParameter('cmf_seo.title.default');
        $bondBy = $this->container->getParameter('cmf_seo.title.bond_by');

        switch ($this->container->getParameter('cmf_seo.title.strategy')) {
            case 'prepend':
                return $contentTitle.$bondBy.$defaultTitle;
            case 'append':
                return $defaultTitle .$bondBy. $contentTitle;
            case 'replace':
                return $contentTitle;
            default:
                return $defaultTitle;
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
