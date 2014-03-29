<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoExtractorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * This presentation model prepares the data for the SeoPage service of the
 * SonataSeoBundle, which is able to provide the values to its Twig helpers.
 *
 * Preparing means:
 * Create the title/description by using the configured
 * translation keys with the help of Symfony's translator and the contents
 * SeoMetadata value as parameter.
 *
 * The original_route_pattern will decide how to handle duplicate
 * content. If it is set to canonical a canonical link is created by an Twig helper
 * (url must be set to the SeoPage), otherwise the url is set to the redirectResponse property
 * which triggers an redirectResponse.
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
     * @var bool
     */
    private $redirectResponse = false;

    /**
     * @var SeoExtractorInterface[]
     */
    private $strategies = array();

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var SeoConfigValues
     */
    private $configValues;

    /**
     * The constructor will set the injected SeoPage - the service of
     * sonata which is responsible for storing the seo data.
     *
     * @param SeoPage             $sonataPage
     * @param TranslatorInterface $translator
     * @param SeoConfigValues     $configValues
     */
    public function __construct(SeoPage $sonataPage, TranslatorInterface $translator, SeoConfigValues $configValues)
    {
        $this->sonataPage = $sonataPage;
        $this->translator = $translator;
        $this->configValues = $configValues;
    }

    /**
     * @param RedirectResponse $redirect
     */
    private function setRedirectResponse(RedirectResponse $redirect)
    {
        $this->redirectResponse = $redirect;
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectResponse()
    {
        return $this->redirectResponse;
    }

    /**
     * Method to add strategies by the compiler pass.
     *
     * @param SeoExtractorInterface $extractor
     */
    public function addExtractor(SeoExtractorInterface $extractor)
    {
        $this->strategies[] = $extractor;
    }

    /**
     * Get the SeoMetadata based on the content document.
     *
     * @param $contentDocument
     * @return SeoMetadata
     */
    private function getSeoMetadata($contentDocument)
    {

        $seoMetadata = $contentDocument instanceof SeoAwareInterface
            ? clone $contentDocument->getSeoMetadata()
            : new SeoMetadata()
        ;

        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($contentDocument)) {
                $strategy->updateMetadata($contentDocument, $seoMetadata);
            }
        }

        return $seoMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function updateSeoPage($contentDocument)
    {
        $seoMetadata = $this->getSeoMetadata($contentDocument);
        $translationDomain = $this->configValues->getTranslationDomain();

        if ($seoMetadata->getTitle()) {
            $pageTitle = null !== $this->configValues->getTitleKey()
                ? $this->translator->trans(
                    $this->configValues->getTitleKey(),
                    array('%content_title%' => $seoMetadata->getTitle()),
                    $translationDomain
                )
                : $seoMetadata->getTitle();

            $this->sonataPage->setTitle($pageTitle);
            $this->sonataPage->addMeta('names', 'title', $pageTitle);
        }

        if ($seoMetadata->getMetaDescription()) {
            $pageDescription = null !== $this->configValues->getDescriptionKey()
                ? $this->translator->trans(
                    $this->configValues->getDescriptionKey(),
                    array('%content_description%' => $seoMetadata->getMetaDescription()),
                    $translationDomain
                )
                : $seoMetadata->getMetaDescription();

            $this->sonataPage->addMeta(
                'names',
                'description',
                $pageDescription
            );
        }

        if ($seoMetadata->getMetaKeywords()) {
            $this->sonataPage->addMeta(
                'names',
                'keywords',
                $this->createKeywords($seoMetadata->getMetaKeywords())
            );
        }

        $url = $seoMetadata->getOriginalUrl();
        if ($url) {
            switch ($this->configValues->getOriginalRoutePattern()) {
                case 'canonical':
                    $this->sonataPage->setLinkCanonical($url);
                    break;
                case 'redirect':
                    $this->setRedirectResponse(
                        new RedirectResponse($url)
                    );
                    break;
            }
        }

    }

    /**
     * This method will use sonatas default values (if set) to
     * create a concatenated list of keywords.
     *
     * @param $contentKeywords
     * @return string
     */
    private function createKeywords($contentKeywords)
    {
        $metas = $this->sonataPage->getMetas();
        $sonataKeywords = isset($metas['names']['keywords'][0])
           ? $metas['names']['keywords'][0]
           : '';

        return ('' !== $sonataKeywords ? $sonataKeywords.', ' : '') . $contentKeywords;
    }
}
