<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Sonata\SeoBundle\Seo\SeoPage;

/**
 * This presentation model prepares the data for the SeoPage service of the
 * SonataSeoBundle which is able to provide the values to its twig helpers.
 *
 * Preparing means combining the title value of the SeoMetadata and the default
 * value defined in the cmf_seo.title.default parameter. Both strings are
 * concatenated by an separator depending on the strategy set in the config.
 *
 * The content config under cmf_seo.content gives a strategy how to handle duplicate
 * content. If it is set to canonical a canonical link is created by an twig helper
 * (url must be set to the SeoPage), otherwise the url is set to the redirect property
 * which triggers an redirect.
 *
 * Class SeoPresentation
 */
class SeoPresentation implements SeoPresentationInterface
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
     * @var bool | false
     */
    private $redirect = false;

    /**
     * storing the content parameters - config values under cmf_seo.content
     * @var array
     */
    private $contentParameters;

    /**
     * storing the title parameters - config values under cmf_seo.title
     * @var array
     */
    private $titleParameters;

    /**
     * to store the current locale injected by DIC
     *
     * @var string
     */
    private $locale;

    /**
     * The constructor will set the injected SeoPage - the service of
     * sonata which is responsible for storing the seo data.
     *
     * @param SeoPage $sonataPage
     */
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
     * {@inheritDoc}
     */
    public function setTitleParameters(array $titleParameters)
    {
        $this->titleParameters = $titleParameters;
    }

    /**
     * This method is the setter injection for the content parameters which contain strategies for
     * duplicate content.
     *
     * @param array $contentParameters
     * @return mixed
     */
    public function setContentParameters(array $contentParameters)
    {
        $this->contentParameters = $contentParameters;
    }

    /**
     * {@inheritDoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
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

            //set the title to SeoPage and  a meta field
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
        switch ($this->contentParameters['strategy']) {
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
    private function createTitle()
    {
        $defaultTitle = $this->doMultilangDecision($this->titleParameters['default']);
        $separator = $this->titleParameters['separator'];
        $contentTitle = $this->seoMetadata->getTitle();

        if ('' == $defaultTitle) {
            return $contentTitle;
        }

        switch ($this->titleParameters['strategy']) {
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
     * depending on the current locale and the setting for the default title this
     * method will return the default title as a string.
     *
     * @param array | string $defaultTitle
     * @return array|string
     */
    private function doMultilangDecision($defaultTitle)
    {
        if (is_string($defaultTitle)) {
            return $defaultTitle;
        }

        if (is_array($defaultTitle) && isset($defaultTitle[$this->locale])) {
            return $defaultTitle[$this->locale];
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
     * setter for the redirect property
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
