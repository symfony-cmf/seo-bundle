<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Cache;

/**
 * Contains the cached data for one particular content object.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class CachedCollection implements \IteratorAggregate, \Serializable
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var null|string
     */
    private $resource;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @param array       $data
     * @param null|string $resource   The path to the file of the content object, this is
     *                                used to determine if the cache needs to be updated
     */
    public function __construct(array $data, $resource = null)
    {
        $this->data = $data;
        $this->resource = $resource;
        $this->createdAt = time();
    }

    /**
     * Creates a CachedCollection based on the object and data to cache.
     *
     * @param object|string $objectOrClass Object instance or FQCN
     * @param array         $data
     *
     * @return static
     */
    public static function createFromObject($objectOrClass, array $data)
    {
        $class = is_object($objectOrClass) ? get_class($objectOrClass) : $objectOrClass;

        static $fileLocations = [];
        if (!isset($fileLocations[$class])) {
            $fileLocations[$class] = (new \ReflectionClass($objectOrClass))->getFileName();
        }

        return new static($data, $fileLocations[$class]);
    }

    public static function generateCacheItemKey($type, $class)
    {
        return sprintf('cmf_seo.%s.%s', $type, str_replace('\\', '.', $class));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Checks if the cache needs to be updated or not.
     *
     * @param null|int $timestamp
     *
     * @return bool whether cache needs to be updated.
     */
    public function isFresh($timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = $this->createdAt;
        }

        if (!file_exists($this->resource)) {
            return false;
        }

        if ($timestamp < filemtime($this->resource)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->data,
            $this->resource,
            $this->createdAt,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($data)
    {
        list(
            $this->data,
            $this->resource,
            $this->createdAt
        ) = unserialize($data);
    }
}
