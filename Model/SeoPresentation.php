<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoExtractorInterface;
use Symfony\Component\Translation\Translator;

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
    protected $sonataPage;

    /**
     * SeoParameters, set in the configuration.
     */
    private $seoParameters;

    /**
     * @var bool
     */
    protected $redirectResponse = false;

    /**
     * @var SeoExtractorInterface[]
     */
    protected $strategies = array();

    /**
     * @var Translator
     */
    private $translator;


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
     * @param RedirectResponse $redirect
     */
    protected function setRedirectResponse(RedirectResponse $redirect)
    {
        $this->redirectResponse = $redirect;
    }

    /**
     * @return bool|RedirectResponse
     */
    public function getRedirectResponse()
    {
        return $this->redirectResponse;
    }

    /**
     * Setter for the seo parameters.
     *
     * @param array $seoParameters
     */
    public function setSeoParameters(array $seoParameters)
    {
        $this->seoParameters = $seoParameters;
    }

    /**
     * Method to add strategies by the compiler pass.
     *
     * @param SeoExtractorInterface $strategy
     */
    public function addExtractor(SeoExtractorInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    /**
     * Setter for the translator.
     *
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Get the SeoMetadata based on the content document.
     *
     * @param $contentDocument
     * @return SeoMetadata
     */
    protected function getSeoMetadata($contentDocument)
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
     * This method will update sonatas SeoPage service.
     *
     * Depending on the contents SeoMetadata it will set
     * a title, a meta description, meta keywords and
     * (depending on the pattern) the canonical link or
     * creates a RedirectResponse.
     */
    public function updateSeoPage($contentDocument)
    {
        $seoMetadata = $this->getSeoMetadata($contentDocument);
        $translationDomain = $this->seoParameters['translation_domain'];

        if ($seoMetadata->getTitle()) {
            $pageTitle = $this->translator->trans(
                $this->seoParameters['title_key'],
                array('title' => $seoMetadata->getTitle()),
                $translationDomain
            );

            $this->sonataPage->setTitle($pageTitle);
            $this->sonataPage->addMeta('names', 'title', $pageTitle);
        }

        if ($seoMetadata->getMetaDescription()) {
            $this->sonataPage->addMeta(
                'names',
                'description',
                $this->translator->trans(
                    $this->seoParameters['description_key'],
                    array('description' => $seoMetadata->getMetaDescription()),
                    $translationDomain
                )
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
            switch ($this->seoParameters['original_route_pattern']) {
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
