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

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Mapping\ClassMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * This guesser will add last modified date of an document to the url information, that can be rendered
 * to the sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class LastModifiedGuesser implements GuesserInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * LastModifiedGuesser constructor.
     *
     * @param ManagerRegistry $manager
     */
    public function __construct(ManagerRegistry $manager)
    {
        $this->managerRegistry = $manager;
    }

    /**
     * Updates UrlInformation with new values if they are not already set.
     *
     * @param UrlInformation $urlInformation the value object to update
     * @param object         $object         the sitemap element to get values from
     * @param string         $sitemap        name of the sitemap being built
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap)
    {
        if (null !== $urlInformation->getLastModification()) {
            return;
        }

        $className = ClassUtils::getRealClass(get_class($object));
        $manager = $this->managerRegistry->getManagerForClass($className);
        if (!$manager instanceof DocumentManager) {
            return;
        }

        /** @var ClassMetadata $metadata */
        $metadata = $manager->getClassMetadata($className);
        $mixins = $metadata->getMixins();

        if (!in_array('mix:lastModified', $mixins)) {
            return;
        }

        $fieldName = $this->getFieldName($metadata);
        if (null === $fieldName) {
            return;
        }

        $urlInformation->setLastModification($metadata->getFieldValue($object, $fieldName));
    }

    private function getFieldName(ClassMetadata $metadata)
    {
        foreach ($metadata->getFieldNames() as $fieldName) {
            $field = $metadata->getFieldMapping($fieldName);
            if ('jcr:lastModified' == $field['property']) {
                return $fieldName;
            }
        }

        return;
    }
}
