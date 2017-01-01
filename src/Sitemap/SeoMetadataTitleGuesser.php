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
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;

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
     * {@inheritdoc}.
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
