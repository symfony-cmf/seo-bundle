<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Guesser that sets the location by building the URL of the object.
 *
 * This will usually require the DynamicRouter from the CmfRoutingBundle.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class LocationGuesser implements GuesserInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->urlGenerator = $router;
    }

    /**
     * {@inheritdoc}.
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap)
    {
        if ($urlInformation->getLocation()) {
            return;
        }

        $urlInformation->setLocation($this->urlGenerator->generate($object, [], UrlGeneratorInterface::ABSOLUTE_URL));
    }
}
