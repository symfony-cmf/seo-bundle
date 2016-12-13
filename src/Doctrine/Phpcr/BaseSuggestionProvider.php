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

use PHPCR\Util\PathHelper;
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
    public function __construct(ManagerRegistry $managerRegistry, $routeBasePaths)
    {
        $this->managerRegistry = $managerRegistry;
        $this->routeBasePaths = (array) $routeBasePaths;
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
     * @return null|object
     */
    protected function findParentRoute($requestedPath)
    {
        $manager = $this->getManagerForClass('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route');
        $parentPaths = array();

        foreach ($this->routeBasePaths as $basepath) {
            $parentPaths[] = PathHelper::getParentPath($basepath.$requestedPath);
        }

        $parentRoutes = $manager->findMany(null, $parentPaths);
        if (0 === count($parentRoutes)) {
            return;
        }

        return $parentRoutes->first();
    }
}
