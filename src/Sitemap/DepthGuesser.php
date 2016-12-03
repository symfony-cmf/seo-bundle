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

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * This guesser will add the depth of a document persisted on a phpcr node.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class DepthGuesser implements GuesserInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var int The depth of the content base path as the depth offset.
     */
    private $offset;

    /**
     * DepthGuesser constructor.
     *
     * @param ManagerRegistry $managerRegistry
     * @param $contentBasePath
     */
    public function __construct(ManagerRegistry $managerRegistry, $contentBasePath)
    {
        $this->managerRegistry = $managerRegistry;
        $this->offset = ('/' === $contentBasePath) ? 1 : substr_count($contentBasePath, '/') + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function guessValues(UrlInformation $urlInformation, $object, $sitemap)
    {
        if (null !== $urlInformation->getDepth()) {
            return;
        }

        $manager = $this->managerRegistry->getManagerForClass(ClassUtils::getRealClass(get_class($object)));
        if (!$manager instanceof DocumentManager) {
            return;
        }

        $node = $manager->getNodeForDocument($object);
        if (null === $node) {
            return;
        }
        $urlInformation->setDepth($node->getDepth() - $this->offset);
    }
}
