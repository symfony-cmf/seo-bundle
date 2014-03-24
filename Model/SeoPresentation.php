<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoExtractorInterface;

/**
 * This presentation model prepares the data for the SeoPage service of the
 * SonataSeoBundle, which is able to provide the values to its Twig helpers.
 *
 * Preparing means combining the title value of the SeoMetadata and the default
 * value defined in the cmf_seo.title.default parameter. Both strings are
 * concatenated by an separator depending on the pattern set in the config.
 *
 * The content config under cmf_seo.content gives a pattern how to handle duplicate
 * content. If it is set to canonical a canonical link is created by an Twig helper
 * (url must be set to the SeoPage), otherwise the url is set to the redirectResponse property
 * which triggers an redirectResponse.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoPresentation implements SeoPresentationInterface
{
    /**
     * Storing the content parameters - config values under cmf_seo.content.
     *
     * @var array
     */
    protected $contentParameters;

    /**
     * Storing the title parameters - config values under cmf_seo.title.
     *
     * @var array
     */
    protected $titleParameters;

    /**
     * @var bool
     */
    protected $redirectResponse = false;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var SeoExtractorInterface[]
     */
    protected $strategies = array();

    /**
     * Setter for the redirectResponse property.
     *
     * @param RedirectResponse $redirect
     */
    protected function setRedirectResponse(RedirectResponse $redirect)
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
     * This method is needed to get the default title parameters injected. They are used for
     * concatenating the default values and the seo meta data or defining the pattern for that.
     *
     * @param array $titleParameters
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
     */
    public function setContentParameters(array $contentParameters)
    {
        $this->contentParameters = $contentParameters;
    }

    /**
     * The document manager is needed to detect the current locale of the document.
     *
     * @param \Doctrine\Bundle\PHPCRBundle\ManagerRegistry $managerRegistry
     */
    public function setDoctrineRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Setter for the default locale of the application.
     *
     * If the list of translated titles does not contain the locale of the current document,
     * or the current document has no locale at all, this locale is used instead.
     *
     * @param $locale
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * To get the Document Manager out of the registry, this method needs to be called.
     *
     * @return ObjectManager|DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->managerRegistry->getManager();
    }

    /**
     * Get the applications default locale.
     *
     * @return string
     */
    protected function getApplicationDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * This method uses the DocumentManager to get the documents current locale.
     * @param string
     * @return null|string
     */
    protected function getModelLocale($contentDocument)
    {
        return $this->getDocumentManager()->getUnitOfWork()->getCurrentLocale($contentDocument);
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
     * @var SeoPage
     */
    protected $sonataPage;

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
     * {@inheritDoc}
     */
    public function updateSeoPage($contentDocument)
    {
        $seoMetadata = $this->getSeoMetadata($contentDocument);
        $locale = $this->getModelLocale($contentDocument);

        if ($seoMetadata->getTitle()) {
            $title = $this->createTitle($seoMetadata->getTitle(), $locale);

            $this->sonataPage->setTitle($title);
            $this->sonataPage->addMeta('names', 'title', $title);
        }

        if ($seoMetadata->getMetaDescription()) {
            $this->sonataPage->addMeta(
                'names',
                'description',
                $this->createDescription($seoMetadata->getMetaDescription())
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
            switch ($this->contentParameters['pattern']) {
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
     * Based on the title pattern this method will create the title from the given
     * configs in the seo configuration part.
     *
     * @param $contentTitle
     * @param $locale
     * @return string
     */
    private function createTitle($contentTitle, $locale)
    {
        $defaultTitle = $this->doMultilangDecision($this->titleParameters['default'], $locale);
        $separator = $this->titleParameters['separator'];

        if ('' == $defaultTitle) {
            return $contentTitle;
        }
        switch ($this->titleParameters['pattern']) {
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
     * @param $locale
     * @throws \Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException
     * @return array|string
     */
    private function doMultilangDecision($defaultTitle, $locale)
    {
        if (is_string($defaultTitle)) {
            return $defaultTitle;
        }

        if (is_array($defaultTitle) && isset($defaultTitle[$locale])) {
            return $defaultTitle[$locale];
        }

        //try the applications default locale
        $defaultLocale = $this->getApplicationDefaultLocale();
        if (is_array($defaultTitle) && isset($defaultTitle[$defaultLocale])) {
            return $defaultTitle[$defaultLocale];
        }

        throw new SeoAwareException(
            sprintf(
                'No default value of title found for current document locale %s and applications default %s',
                $locale,
                $defaultLocale
            )
        );
    }

    /**
     * As you can set your default description in the sonata_seo settings and
     * can add some more from your contend, this method will combine both.
     *
     * @param $contentDescription
     * @return string
     */
    private function createDescription($contentDescription)
    {
        $metas = $this->sonataPage->getMetas();
        $sonataDescription = isset($metas['names']['description'][0])
                                ? $metas['names']['description'][0]
                                : '';

        return ('' !== $sonataDescription ? $sonataDescription.'. ' : '') . $contentDescription;
    }

    /**
     * Same as for the previous method. You can set the keywords in your sonata seo
     * setting, but each SeoAwareContent is able to set its own, this method will combine
     * both.
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
