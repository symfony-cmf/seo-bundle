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

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Accepts providers and merges the result of all providers into
 * a combined list of UrlInformation
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ChainProvider implements UrlInformationProviderInterface
{
    /**
     * @var array
     */
    private $providers = array();

    /**
     * {@inheritDoc}
     */
    public function addProvider(UrlInformationProviderInterface $provider, $priority = 0)
    {
        if (empty($this->providers[$priority])) {
            $this->providers[$priority] = array();
        }
        $this->providers[$priority][] = $provider;
    }

    /**
     * @return UrlInformationProviderInterface[]
     */
    private function getSortedProviders()
    {
        $sortedProviders = array();
        ksort($this->providers);

        foreach ($this->providers as $providers) {
            $sortedProviders = array_merge($sortedProviders, $providers);
        };

        return $sortedProviders;
    }

    /**
     * @return UrlInformation[]
     */
    public function getUrlInformation()
    {
        $urlInformation = array();

        foreach ($this->getSortedProviders() as $provider) {
            $urlInformation = array_merge($urlInformation, $provider->getUrlInformation());
        }

        return $urlInformation;
    }
}
