<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts the arbitrary properties from contents implementing the
 * SeoArbitraryPropertiesReadInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ExtraPropertiesExtractor implements SeoExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($content)
    {
        return $content instanceof ExtraPropertiesReadInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param ExtraPropertiesReadInterface $content
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        $properties = $content->getSeoExtraProperties();
        if (is_array($properties)) {
            $properties = new ArrayCollection($properties);
        } elseif (!$properties instanceof Collection) {
            throw new InvalidArgumentException(sprintf('getSeoExtraProperties should return an array or an implementation of Doctrine\Common\Collections\Collection, "%s" given instead.', is_object($properties) ? get_class($properties) : gettype($properties)));
        }

        $seoMetadata->setExtraProperties($properties);
    }
}
