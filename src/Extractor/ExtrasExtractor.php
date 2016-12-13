<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * This strategy extracts additional properties from contents implementing the
 * SeoExtraPropertiesReadInterface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ExtrasExtractor implements ExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($content)
    {
        return $content instanceof ExtrasReadInterface;
    }

    /**
     * {@inheritdoc}
     *
     * @param ExtrasReadInterface $content
     */
    public function updateMetadata($content, SeoMetadataInterface $seoMetadata)
    {
        $allowedTypesMethodMapping = array(
            'property' => 'addExtraProperty',
            'name' => 'addExtraName',
            'http-equiv' => 'addExtraHttp',
        );

        $contentExtras = $content->getSeoExtras();

        foreach ($contentExtras as $type => $extras) {
            if (!array_key_exists($type, $allowedTypesMethodMapping)) {
                throw new InvalidArgumentException(
                    printf(
                        'Extras type %s not in the list of allowed ones %s.',
                        $type,
                        implode(', ', $allowedTypesMethodMapping)
                    )
                );
            }

            foreach ($extras as $key => $value) {
                $seoMetadata->{$allowedTypesMethodMapping[$type]}($key, $value);
            }
        }
    }
}
