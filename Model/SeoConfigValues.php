<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * This is a simple value object for storing the configuration values in a
 * meaningful way.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class SeoConfigValues
{
    /**
     * Specific translation domain to use for title and description.
     *
     * @var string
     */
    private $translationDomain;

    /**
     * The title translation key. The extracted title will be passed to the
     * translator as %content_title%.
     *
     * @var string
     */
    private $titleKey;

    /**
     * The description translation key. The extracted description will be
     * passed to the translator as %content_description%.
     *
     * @var string
     */
    private $descriptionKey;

    /**
     * The original route pattern describes the the way how to handle content
     * with more than one route.
     *
     * This value can be either "canonical" or "redirect".
     *
     * @var string
     */
    private $originalRoutePattern;

    /**
     * @param string $descriptionKey
     */
    public function setDescriptionKey($descriptionKey)
    {
        $this->descriptionKey = $descriptionKey;
    }

    /**
     * @return string
     */
    public function getDescriptionKey()
    {
        return $this->descriptionKey;
    }

    /**
     * @param string $originalRouteStrategy
     */
    public function setOriginalRoutePattern($originalRouteStrategy)
    {
        $this->originalRoutePattern = $originalRouteStrategy;
    }

    /**
     * @return string
     */
    public function getOriginalRoutePattern()
    {
        return $this->originalRoutePattern;
    }

    /**
     * @param string $titleKey
     */
    public function setTitleKey($titleKey)
    {
        $this->titleKey = $titleKey;
    }

    /**
     * @return string
     */
    public function getTitleKey()
    {
        return $this->titleKey;
    }

    /**
     * @param string $translationDomain
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;
    }

    /**
     * @return string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }
}
