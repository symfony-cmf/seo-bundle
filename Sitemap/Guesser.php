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
 * A chain to register all url information guesser by its priority and preferred sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Guesser implements GuesserInterface
{
    /**
     * @var AlternateLocaleProviderInterface
     */
    protected $alternateLocaleProvider;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $defaultChangeFrequency;

    /**
     * @var SeoPresentation
     */
    private $seoPresentation;

    /**
     * @param RouterInterface $router
     * @param SeoPresentation $seoPresentation
     * @param $defaultChangeFrequency
     */
    public function __construct(
        RouterInterface $router,
        SeoPresentation $seoPresentation,
        $defaultChangeFrequency
    ) {
        $this->router = $router;
        $this->seoPresentation = $seoPresentation;
        $this->defaultChangeFrequency = $defaultChangeFrequency;
    }

    /**
     * @param AlternateLocaleProviderInterface $alternateLocaleProvider
     */
    public function setAlternateLocaleProvider(AlternateLocaleProviderInterface $alternateLocaleProvider)
    {
        $this->alternateLocaleProvider = $alternateLocaleProvider;
    }

    /**
     * {@inheritDocs}
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap = 'default')
    {
        $urlInformation->setLocation($this->router->generate($object, array(), true));
        $urlInformation->setChangeFrequency($this->defaultChangeFrequency);

        if ($this->alternateLocaleProvider) {
            $collection = $this->alternateLocaleProvider->createForContent($object);
            $urlInformation->setAlternateLocales($collection->toArray());
        }

        $seoMetadata = $this->seoPresentation->getSeoMetadata($object);
        if (null !== $seoMetadata->getTitle()) {
            $urlInformation->setLabel($seoMetadata->getTitle());
            return $urlInformation;
        }
    }
}
