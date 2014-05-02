<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Cache;

/**
 * Classes implementing this interface are able to cache extractors for
 * content objects.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
interface CacheInterface
{
    /**
     * Fetches extractors from the cache.
     *
     * @param string $class
     *
     * @return ExtractorCollection|null
     */
    public function loadExtractorsFromCache($class);

    /**
     * Saves extractors into the cache.
     *
     * @param string $class
     * @param array  $extractors
     */
    public function putExtractorsInCache($class, array $extractors);
}
