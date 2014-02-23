<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareContentException;
use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException;

/**
 * This presentation model prepares the data for the SeoPage service of the
 * SonataSeoBundle, which is able to provide the values to its Twig helpers.
 *
 * Preparing means combining the title value of the SeoMetadata and the default
 * value defined in the cmf_seo.title.default parameter. Both strings are
 * concatenated by an separator depending on the strategy set in the config.
 *
 * The content config under cmf_seo.content gives a strategy how to handle duplicate
 * content. If it is set to canonical a canonical link is created by an Twig helper
 * (url must be set to the SeoPage), otherwise the url is set to the redirect property
 * which triggers an redirect.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
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
     * @var bool
     */
    private $redirect = false;

    /**
     * Storing the content parameters - config values under cmf_seo.content.
     *
     * @var array
     */
    private $contentParameters;

    /**
     * Storing the title parameters - config values under cmf_seo.title.
     *
     * @var array
     */
    private $titleParameters;

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var SeoAwareInterface
     */
    private $contentDocument;

    /**
     * @var string
     */
    private $defaultLocale;

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
    public function setContentDocument(SeoAwareInterface $contentDocument)
    {
        $this->contentDocument = $contentDocument;
    }

    /**
     * {@inheritDoc}
     */
    public function setTitleParameters(array $titleParameters)
    {
        $this->titleParameters = $titleParameters;
    }

    /**
     * {@inheritDoc}
     */
    public function setContentParameters(array $contentParameters)
    {
        $this->contentParameters = $contentParameters;
    }

    /**
     * {@inheritDoc}
     */
    public function setDocumentManager(DocumentManager $documentManager)
    {
        $this->dm = $documentManager;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     *  this method will combine all settings directly in the sonata_seo configuration with
     *  the given values of the current content
     */
    public function setMetaDataValues()
    {
        //get the current seo metadata out of the document
        $this->seoMetadata = $this->contentDocument->getSeoMetadata();

        //based on the title strategy, the helper method will set the complete title
        if ($this->seoMetadata->getTitle() !== null) {
            $title = $this->createTitle();

            //set the title to SeoPage and  a meta field
            $this->sonataPage->setTitle($title);
            $this->sonataPage->addMeta('names', 'title', $title);
        }

        if ($this->seoMetadata->getMetaDescription() !== null) {
            $this->sonataPage->addMeta(
                'names',
                'description',
                $this->createDescription()
            );
        }

        if ($this->seoMetadata->getMetaKeywords() !== null) {
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
     * Based on the title strategy this method will create the title from the given
     * configs in the seo configuration part.
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
     * Depending on the current locale and the setting for the default title this
     * method will return the default title as a string.
     *
     * @param  array|string $defaultTitle
     * @throws \Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException
     * @return array|string
     */
    private function doMultilangDecision($defaultTitle)
    {
        if (is_string($defaultTitle)) {
            return $defaultTitle;
        }


        // try the current location of the document, seoMetadata should have the same
        $currentLocale = $this->dm->getUnitOfWork()->getCurrentLocale($this->seoMetadata);
        if (is_array($defaultTitle) && isset($defaultTitle[$currentLocale])) {
            return $defaultTitle[$currentLocale];
        }

        if (is_array($defaultTitle) && isset($defaultTitle[$this->defaultLocale])) {
            return $defaultTitle[$this->defaultLocale];
        }

        throw new SeoAwareException(
            sprintf(
                'No default value of title found for current document locale %s and applications default %s',
                $currentLocale,
                $this->defaultLocale
            )
        );

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
     * Same as for the previous method. You can set the keywords in your sonata seo
     * setting, but each SeoAwareContent is able to set its own, this method will combine
     * both.
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
     * Setter for the redirect property.
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
