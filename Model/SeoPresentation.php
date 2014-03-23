<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use PHPCR\Util\UUIDHelper;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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
class SeoPresentation extends AbstractSeoPresentation
{
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
     * @param  array|string                                               $defaultTitle
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
