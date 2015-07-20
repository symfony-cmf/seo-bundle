<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Component\Routing\RouterInterface;

/**
 * Set a default change frequency if none has been set.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DefaultChangeFrequencyGuesser implements GuesserInterface
{
    /**
     * @var string
     */
    private $defaultChangeFrequency;

    /**
     * @param string $defaultChangeFrequency
     *
     * @see UrlInformation::setChangeFrequency for information on this parameter.
     */
    public function __construct($defaultChangeFrequency) {
        $this->defaultChangeFrequency = $defaultChangeFrequency;
    }

    /**
     * {@inheritDocs}
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap)
    {
        if ($urlInformation->getChangeFrequency()) {
            return;
        }

        $urlInformation->setChangeFrequency($this->defaultChangeFrequency);
    }
}
