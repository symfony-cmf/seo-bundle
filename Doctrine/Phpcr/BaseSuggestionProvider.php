<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\SeoBundle\SuggestionProviderInterface;

/**
 * Abstract suggestion provider for those who needs make use of base
 * phpcr-odm classes.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
abstract class BaseSuggestionProvider implements SuggestionProviderInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * By concatenating the routeBasePaths and the url
     * we will get the absolute path a route document
     * should be persisted with.
     *
     * @var array
     */
    protected $routeBasePaths;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param array           $routeBasePath
     */
    public function __construct(ManagerRegistry $managerRegistry, array $routeBasePaths)
    {
        $this->managerRegistry = $managerRegistry;
        $this->routeBasePaths = $routeBasePaths;
    }

    /**
     * @param string $class
     *
     * @return null|DocumentManager
     */
    public function getManagerForClass($class)
    {
        return $this->managerRegistry->getManagerForClass($class);
    }

    /**
     * Finds the parent route document by concatenating the basepaths with the 
     * requested path.
     *
     * @return null|\Traversable
     */
    protected function findParentRoute($requestedPath)
    {
        foreach ($this->routeBasePaths as $basepath) {
            $parentPath = PathHelper::getParentPath($basepath.$requestedPath);

            $parentRoute = $manager->find(null, $parentPath);
            if (null !== $parentRoute) {
                break;
            }
        }

        return $parentRoute;
    }
}
