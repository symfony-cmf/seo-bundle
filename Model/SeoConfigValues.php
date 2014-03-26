<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * This is a simple value object for storing the config values
 * in a meaningful way.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class SeoConfigValues
{
    /**
     * If you are using a separate translation domain for your keys
     * (title, description) you can set it here.
     *
     * @var string
     */
    private $translationDomain;

    /**
     * Use that key for define your default page's title and
     * add a pattern for injecting a content specific title
     * by the parameter %title%.
     *
     * @var string
     */
    private $titleKey;

    /**
     * Use that key for define your default page's description and
     * add a pattern for injecting a content specific description
     * by the parameter %description%.
     *
     * @var string
     */
    private $descriptionKey;

    /**
     * The original route pattern describes the the way how to handle
     * duplicate content for a route.
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
