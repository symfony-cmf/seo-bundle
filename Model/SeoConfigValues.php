<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Symfony\Cmf\Bundle\SeoBundle\Exception\SeoExtractorStrategyException;

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
     * The original URL behaviour decides on how to handle content with several URLs.
     *
     * This value needs to be one of the ORIGINAL_URL_* constants in SeoPresentation.
     *
     * @var string
     */
    private $originalUrlBehaviour;

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
     * @param string $behaviour One of the constants from SeoPresentation.
     */
    public function setOriginalUrlBehaviour($behaviour)
    {
        if (! in_array($behaviour, SeoPresentation::$originalUrlBehaviours)) {
            throw new SeoExtractorStrategyException(
                sprintf('Behaviour "%s" not supported by SeoPresentation.', $behaviour)
            );
        }
        $this->originalUrlBehaviour = $behaviour;
    }

    /**
     * @return string
     */
    public function getOriginalUrlBehaviour()
    {
        return $this->originalUrlBehaviour;
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
