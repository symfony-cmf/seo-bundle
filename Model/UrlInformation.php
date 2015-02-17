<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Model;
use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class UrlInformation
{
    /**
     * Decides whether the content is visible on sitemap.
     *
     * @var bool
     */
    private $visible;

    /**
     * @var string
     */
    private $location;

    /**
     * @var \DateTime
     */
    private $lastModification;

    /**
     * @var string One of the official/allowed.
     */
    private $changeFrequency;

    /**
     * @var float
     */
    private $priority;

    /**
     * @var array
     */
    private $allowedChangeFrequencies = array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never');

    /**
     * @var string $label As a string to display the route i.e. in html views.
     */
    private $label;

    /**
     * @var AlternateLocale[]
     */
    private $alternateLocales;

    public function __construct()
    {
        $this->alternateLocales = array();
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param boolean $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    public function toArray()
    {
        $result = array(
            'loc'               => $this->location,
            'label'             => $this->label,
            'changefreq'        => $this->changeFrequency,
            'lastmod'           => $this->lastModification,
            'priority'          => $this->priority,
            'alternate_locales' => array()
        );

        foreach ($this->alternateLocales as $locale) {
            $result['alternate_locales'][] = array('href' => $locale->href, 'href_locale' => $locale->hrefLocale);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getChangeFrequency()
    {
        return $this->changeFrequency;
    }

    /**
     * @param string $changeFrequency One of the official/allowed ones.
     *
     * @return $this
     */
    public function setChangeFrequency($changeFrequency)
    {
        if (!in_array($changeFrequency, $this->allowedChangeFrequencies)) {
            throw new InvalidArgumentException(
                sprintf('Invalid change frequency use one of %s.', implode(', ', $this->allowedChangeFrequencies))
            );
        }

        $this->changeFrequency = $changeFrequency;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastModification()
    {
        return $this->lastModification;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return $this
     */
    public function setLastModification(\DateTime $dateTime)
    {
        $lastmod = $dateTime->format('c');
        $this->lastModification = $lastmod;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     *
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return float
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param float $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return array|AlternateLocale[]
     */
    public function getAlternateLocales()
    {
        return $this->alternateLocales;
    }

    /**
     * @param array|AlternateLocale[] $alternateLocales
     */
    public function setAlternateLocales($alternateLocales)
    {
        $this->alternateLocales = $alternateLocales;
    }

    /**
     * @param AlternateLocale $alternateLocale
     */
    public function addAlternateLocale(AlternateLocale $alternateLocale)
    {
        $this->alternateLocales[] = $alternateLocale;
    }
}
