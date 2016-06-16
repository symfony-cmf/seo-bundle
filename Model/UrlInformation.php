<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
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
     * @var string As a string to display the route i.e. in html views.
     */
    private $label;

    /**
     * @var AlternateLocale[]
     */
    private $alternateLocales;

    /**
     * @var int|null
     */
    private $depth;

    public function __construct()
    {
        $this->alternateLocales = array();
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     *
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function toArray()
    {
        $result = array(
            'loc' => $this->location,
            'label' => $this->label,
            'changefreq' => $this->changeFrequency,
            'lastmod' => $this->lastModification,
            'priority' => $this->priority,
            'alternate_locales' => array(),
            'depth' => $this->depth,
        );
        foreach ($result as $key => $value) {
            if (null === $value) {
                unset($result[$key]);
            }
        }

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
     * According to http://www.sitemaps.org, valid values should be always|hourly|daily|weekly|monthly|yearly|never.
     *
     * @param string $changeFrequency One of the official/allowed change frequencies.
     *
     * @return $this
     */
    public function setChangeFrequency($changeFrequency)
    {
        if (!in_array($changeFrequency, $this->allowedChangeFrequencies)) {
            throw new InvalidArgumentException(
                sprintf('Invalid change frequency "%s", use one of %s.', $changeFrequency, implode(', ', $this->allowedChangeFrequencies))
            );
        }

        $this->changeFrequency = $changeFrequency;

        return $this;
    }

    /**
     * @return \DateTime
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
     *
     * @return $this
     */
    public function setAlternateLocales($alternateLocales)
    {
        $this->alternateLocales = $alternateLocales;

        return $this;
    }

    /**
     * @param AlternateLocale $alternateLocale
     *
     * @return $this
     */
    public function addAlternateLocale(AlternateLocale $alternateLocale)
    {
        $this->alternateLocales[] = $alternateLocale;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param int|null $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }
}
