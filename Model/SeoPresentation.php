<?php
/**
 * User: maximilian
 * Date: 2/7/14
 * Time: 11:18 PM
 *
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SeoPresentation implements
    SeoPresentationInterface,
    ContainerAwareInterface
{
    /**
     * @var SeoPage
     */
    private $sonataPage;

    /**
     * @var SeoMetadataInterface
     */
    private $seoMetadata;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var bool | false
     */
    private $redirect = false;

    public function __construct(SeoPage $sonataPage)
    {
        $this->sonataPage = $sonataPage;
    }

    /**
     * {@inheritDoc}
     */
    public function setSeoMetadata(SeoMetadataInterface $seoMetadata)
    {
        $this->seoMetadata = $seoMetadata;
    }

    public function setMetaDataValues()
    {
        //based on the title strategy, the helper method will set the complete title
        if ($this->container->getParameter('cmf_seo.title')) {
            $title = $this->createTitle();
            $this->sonataPage->setTitle($title);
            $this->sonataPage->addMeta('property', 'title', $title);
        }

        //set the description, additional to the default one
        $this->sonataPage->addMeta(
            'name',
            'description',
            $this->seoMetadata->getMetaDescription() . ' '.$this->container->getParameter('cmf_seo.description')
        );

        //set the Keywords combined with the default ones
        $this->sonataPage->addMeta(
            'property',
            'keywords',
            $this->seoMetadata->getMetaKeywords() . ', '.$this->container->getParameter('cmf_seo.keys')
        );

        //if the strategy for duplicate content is canonical, the service will trigger an canonical link
        switch ($this->container->getParameter('cmf_seo.content.strategy')) {
            case 'canonical':
                $this->sonataPage->setLinkCanonical($this->seoMetadata->getOriginalUrl());
                break;
            case 'redirect':
                $this->setRedirect($this->seoMetadata->getOriginalUrl());
                break;
        }
    }

    /**
     * based on the title strategy this method will create the title from the given
     * configs in the seo configuration part
     *
     * @return string
     */
    protected function createTitle()
    {
        $contentTitle = $this->seoMetadata->getTitle();
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

    /**
     *
     * @param $redirect
     */
    private function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

}
