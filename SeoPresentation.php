<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\ConfigValues;
use Symfony\Cmf\Bundle\SeoBundle\Cache\CacheInterface;
use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;

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
        SeoPresentation::ORIGINAL_URL_CANONICAL,
        SeoPresentation::ORIGINAL_URL_REDIRECT,
    );

    /**
     * @var SeoPage
     */
    private $sonataPage;

    /**
     * @var boolean
     */
    private $redirectResponse = false;

    /**
     * @var ExtractorInterface[]
     */
    private $extractors = array();

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ConfigValues
     */
    private $configValues;

    /**
     * @var null|CacheInterface
     */
    private $cache;

    /**
     * The constructor will set the injected SeoPage - the service of
     * sonata which is responsible for storing the seo data.
     *
     * @param SeoPage             $sonataPage
     * @param TranslatorInterface $translator
     * @param ConfigValues        $configValues
     * @param CacheInterface      $cache
     */
    public function __construct(
        SeoPage $sonataPage,
        TranslatorInterface $translator,
        ConfigValues $configValues,
        CacheInterface $cache = null
    ) {
        $this->sonataPage = $sonataPage;
        $this->translator = $translator;
        $this->configValues = $configValues;
        $this->cache = $cache;
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
     * Adds extractors.
     *
     * @param ExtractorInterface $extractor
     */
    public function addExtractor(ExtractorInterface $extractor)
    {
        $this->extractors[] = $extractor;
    }

    /**
     * Gets the SeoMetadata based on the content that contains the content.
     *
     * @param object $content
     *
     * @throws Exception\InvalidArgumentException
     * @return SeoMetadata
     */
    private function getSeoMetadata($content)
    {
        if ($content instanceof SeoAwareInterface) {
            $contentSeoMetadata = $content->getSeoMetadata();

            if ($contentSeoMetadata instanceof SeoMetadataInterface) {
                $seoMetadata = $this->copyMetadata($contentSeoMetadata);
            } elseif (null === $contentSeoMetadata) {
                $seoMetadata = new SeoMetadata();
                $content->setSeoMetadata($seoMetadata); // make sure it has metadata the next time
            } else {
                throw new InvalidArgumentException(
                    sprintf(
                        'getSeoMetadata must return either an instance of SeoMetadataInterface or null, "%s" given',
                        is_object($contentSeoMetadata) ? get_class($contentSeoMetadata) : gettype($contentSeoMetadata)
                    )
                );
            }
        } else {
            $seoMetadata = new SeoMetadata();
        }

        $cachingAvailable = (boolean) $this->cache;
        if ($cachingAvailable) {
            $extractors = $this->cache->loadExtractorsFromCache(get_class($content));

            if (null === $extractors || !$extractors->isFresh()) {
                $extractors = $this->getExtractorsForContent($content);
                $this->cache->putExtractorsInCache(get_class($content), $extractors);
            }
        } else {
            $extractors = $this->getExtractorsForContent($content);
        }

        foreach ($extractors as $extractor) {
            $extractor->updateMetadata($content, $seoMetadata);
        }

        return $seoMetadata;
    }

    /**
     * Returns the extractors for content.
     *
     * @param object $content
     *
     * @return array
     */
    private function getExtractorsForContent($content)
    {
        return array_filter($this->extractors, function ($extractor) use ($content) {
            return $extractor->supports($content);
        });
    }

    /**
     * {@inheritDoc}
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
            $this->sonataPage->addMeta('name', 'title', $pageTitle);
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

        return ('' !== $sonataKeywords ? $sonataKeywords.', ' : '') . $contentKeywords;
    }

    /**
     * Copy the metadata object to sanitize it and remove doctrine traces.
     *
     * @param SeoMetadataInterface $contentSeoMetadata
     *
     * @return SeoMetadata
     */
    private function copyMetadata(SeoMetadataInterface $contentSeoMetadata)
    {
        $metadata = new SeoMetadata();
        $metadata->setTitle($contentSeoMetadata->getTitle());
        $metadata->setMetaKeywords($contentSeoMetadata->getMetaKeywords());
        $metadata->setMetaDescription($contentSeoMetadata->getMetaDescription());
        $metadata->setOriginalUrl($contentSeoMetadata->getOriginalUrl());
        $metadata->setExtraProperties($contentSeoMetadata->getExtraProperties()?:array());
        $metadata->setExtraNames($contentSeoMetadata->getExtraNames()?:array());
        $metadata->setExtraHttp($contentSeoMetadata->getExtraHttp()?:array());

        return $metadata;
    }
}
