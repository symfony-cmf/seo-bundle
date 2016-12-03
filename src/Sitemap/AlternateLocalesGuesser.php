<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Guesser to set alternate locales from an AlternateLocaleProviderInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class AlternateLocalesGuesser implements GuesserInterface
{
    /**
     * @var AlternateLocaleProviderInterface
     */
    private $alternateLocaleProvider;

    public function __construct(AlternateLocaleProviderInterface $alternateLocaleProvider)
    {
        $this->alternateLocaleProvider = $alternateLocaleProvider;
    }

    /**
     * {@inheritdoc}.
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap)
    {
        if ($urlInformation->getAlternateLocales()) {
            return;
        }

        $collection = $this->alternateLocaleProvider->createForContent($object);
        $urlInformation->setAlternateLocales($collection->toArray());
    }
}
