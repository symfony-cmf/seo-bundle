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
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Component\Routing\RouterInterface;

/**
 * Guess the title with the help of SEO metadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadataTitleGuesser implements GuesserInterface
{
    /**
     * @var SeoPresentation
     */
    private $seoPresentation;

    public function __construct(
        SeoPresentation $seoPresentation
    ) {
        $this->seoPresentation = $seoPresentation;
    }

    /**
     * {@inheritDocs}
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap)
    {
        if ($urlInformation->getLabel()) {
            return;
        }

        $seoMetadata = $this->seoPresentation->getSeoMetadata($object);
        if ($seoMetadata->getTitle()) {
            $urlInformation->setLabel($seoMetadata->getTitle());
        }
    }
}
