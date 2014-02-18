<?php

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
     * @var bool|false
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

    /**
     *  this method will combine all settings directly in the sonata_seo configuration with
     *  the given values of the current content
     */
    public function setMetaDataValues()
    {
        //based on the title strategy, the helper method will set the complete title
        if ($this->seoMetadata->getTitle() !== '') {
            $title = $this->createTitle();
            $this->sonataPage->setTitle($title);
            $this->sonataPage->addMeta('names', 'title', $title);
        }

        if ($this->seoMetadata->getMetaDescription() != '') {
            $this->sonataPage->addMeta(
                'names',
                'description',
                $this->createDescription()
            );
        }

        if ($this->seoMetadata->getMetaKeywords() != '') {
            $this->sonataPage->addMeta(
                'names',
                'keywords',
                $this->createKeywords()
            );
        }

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
        $defaultTitle = $this->sonataPage->getTitle();
        $contentTitle = $this->seoMetadata->getTitle();
        $separator = $this->container->getParameter('cmf_seo.title.separator');

        if ('' == $defaultTitle) {
            return $contentTitle;
        }
        switch ($this->container->getParameter('cmf_seo.title.strategy')) {
            case 'prepend':
                return $contentTitle.$separator.$defaultTitle;
            case 'append':
                return $defaultTitle .$separator. $contentTitle;
            case 'replace':
                return $contentTitle;
            default:
                return $defaultTitle;
        }
    }

    /**
     * As you can set your default description in the sonata_seo settings and
     * can add some more from your contend, this method will combine both.
     *
     * @return string
     */
    private function createDescription()
    {
        $sonataDescription = isset($this->sonataPage->getMetas()['names']['description'][0])
                                ? $this->sonataPage->getMetas()['names']['description'][0]
                                : array();

        return $sonataDescription .'. '. $this->seoMetadata->getMetaDescription();
    }

    /**
     * same as for the previous method. You can set the keywords in your sonata seo
     * setting, but each SeoAwareContent is able to set its own, this method will combine
     * both
     *
     * @return string
     */
    private function createKeywords()
    {
        $sonataKeywords = isset($this->sonataPage->getMetas()['names']['keywords'][0])
                                ? $this->sonataPage->getMetas()['names']['keywords'][0]
                                : array();

        return $sonataKeywords .', '. $this->seoMetadata->getMetaKeywords();
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
