<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\ConfigValues;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocaleCollection;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * This presentation model prepares the data for the SeoPage service of the
 * SonataSeoBundle, which is able to provide the values to its Twig helpers.
 *
 * Preparing means that it creates the title/description by using the configured
 * translation keys with the help of the Symfony Translator and the contents
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
     * Original URL should be output as canonical URL.
     */
    const ORIGINAL_URL_CANONICAL = 'canonical';

    /**
     * Redirect to original URL if not currently on original URL.
     */
    const ORIGINAL_URL_REDIRECT = 'redirect';

    public static $originalUrlBehaviours = array(
        self::ORIGINAL_URL_CANONICAL,
        self::ORIGINAL_URL_REDIRECT,
    );

    /**
     * @var SeoPage
     */
    private $sonataPage;

    /**
     * @var bool
     */
    private $redirectResponse = false;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ConfigValues
     */
    private $configValues;

    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var SeoMetadataInterface[]
     */
    private $seoMetadatas = [];

    /**
     * The constructor will set the injected SeoPage - the service of
     * sonata which is responsible for storing the seo data.
     *
     * @param SeoPage             $sonataPage
     * @param TranslatorInterface $translator
     * @param ConfigValues        $configValues
     * @param LoaderInterface     $loader
     */
    public function __construct(
        SeoPage $sonataPage,
        TranslatorInterface $translator,
        ConfigValues $configValues,
        LoaderInterface $loader
    ) {
        $this->sonataPage = $sonataPage;
        $this->translator = $translator;
        $this->configValues = $configValues;
        $this->loader = $loader;
    }

    /**
     * @param RedirectResponse $redirect
     */
    private function setRedirectResponse(RedirectResponse $redirect)
    {
        $this->redirectResponse = $redirect;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectResponse()
    {
        return $this->redirectResponse;
    }

    /**
     * Extract the SEO metadata from this object.
     *
     * @param object $content The content to extract metadata from.
     *
     * @return SeoMetadataInterface
     */
    public function getSeoMetadata($content)
    {
        $hash = spl_object_hash($content);
        if (isset($this->seoMetadatas[$hash])) {
            return $this->seoMetadatas[$hash];
        }

        return $this->seoMetadatas[$hash] = $this->loader->load($content);
    }

    /**
     * {@inheritdoc}
     */
    public function updateSeoPage($content)
    {
        $seoMetadata = $this->getSeoMetadata($content);
        $translationDomain = $this->configValues->getTranslationDomain();

        if ($extraProperties = $seoMetadata->getExtraProperties()) {
            foreach ($extraProperties as $key => $value) {
                $this->sonataPage->addMeta('property', $key, $value);
            }
        }

        if ($extraNames = $seoMetadata->getExtraNames()) {
            foreach ($extraNames as $key => $value) {
                $this->sonataPage->addMeta('name', $key, $value);
            }
        }

        if ($extraHttp = $seoMetadata->getExtraHttp()) {
            foreach ($extraHttp as $key => $value) {
                $this->sonataPage->addMeta('http-equiv', $key, $value);
            }
        }

        if ($seoMetadata->getTitle()) {
            $pageTitle = null !== $this->configValues->getTitle()
                ? $this->translator->trans(
                    $this->configValues->getTitle(),
                    array('%content_title%' => $seoMetadata->getTitle()),
                    $translationDomain
                )
                : $seoMetadata->getTitle();

            $this->sonataPage->setTitle($pageTitle);
        }

        if ($seoMetadata->getMetaDescription()) {
            $pageDescription = null !== $this->configValues->getDescription()
                ? $this->translator->trans(
                    $this->configValues->getDescription(),
                    array('%content_description%' => $seoMetadata->getMetaDescription()),
                    $translationDomain
                )
                : $seoMetadata->getMetaDescription();

            $this->sonataPage->addMeta(
                'name',
                'description',
                $pageDescription
            );
        }

        if ($seoMetadata->getMetaKeywords()) {
            $this->sonataPage->addMeta(
                'name',
                'keywords',
                $this->createKeywords($seoMetadata->getMetaKeywords())
            );
        }

        $url = $seoMetadata->getOriginalUrl();
        if ($url) {
            switch ($this->configValues->getOriginalUrlBehaviour()) {
                case self::ORIGINAL_URL_CANONICAL:
                    $this->sonataPage->setLinkCanonical($url);
                    break;
                case self::ORIGINAL_URL_REDIRECT:
                    $this->setRedirectResponse(
                        new RedirectResponse($url)
                    );
                    break;
            }
        }
    }

    /**
     * Creates a concatenated list of keywords based on sonatas default
     * values.
     *
     * @param string $contentKeywords
     *
     * @return string
     */
    private function createKeywords($contentKeywords)
    {
        $metas = $this->sonataPage->getMetas();
        $sonataKeywords = isset($metas['name']['keywords'][0])
           ? $metas['name']['keywords'][0]
           : '';

        return ('' !== $sonataKeywords ? $sonataKeywords.', ' : '').$contentKeywords;
    }

    /**
     * {inheritDoc}.
     */
    public function updateAlternateLocales(AlternateLocaleCollection $collection)
    {
        foreach ($collection as $alternateLocale) {
            $this->sonataPage->addLangAlternate(
                $alternateLocale->href,
                $alternateLocale->hrefLocale
            );
        }
    }
}
