<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Cmf\Bundle\SeoBundle\Exception\ExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;

/**
 * This is a simple value object for storing the configuration values in a
 * meaningful way.
 *
 * @author Maximilian Berghoff <maximilian.berghoff@gmx.de>
 */
class ConfigValues
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
    private $title;

    /**
     * The description translation key. The extracted description will be
     * passed to the translator as %content_description%.
     *
     * @var string
     */
    private $description;

    /**
     * The original URL behaviour decides on how to handle content with several URLs.
     *
     * This value needs to be one of the ORIGINAL_URL_* constants in SeoPresentation.
     *
     * @var string
     */
    private $originalUrlBehaviour;

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description= $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $behaviour One of the constants from SeoPresentation.
     *
     * @throws ExtractorStrategyException if $behaviour is not supported.
     */
    public function setOriginalUrlBehaviour($behaviour)
    {
        if (! in_array($behaviour, SeoPresentation::$originalUrlBehaviours)) {
            throw new ExtractorStrategyException(
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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title= $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
