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
 * Contains the extractors for one particular content object.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class ExtractorCollection implements \IteratorAggregate, \Serializable
{
    /**
     * @var array
     */
    private $extractors;

    /**
     * @var null|string
     */
    private $resource;

    /**
     * @var integer
     */
    private $createdAt;

    /**
     * @param array       $extractors
     * @param null|string $resource   The path to the file of the content object, this is
     *                                used to determine if the cache needs to be updated
     */
    public function __construct(array $extractors, $resource = null)
    {
        $this->extractors = $extractors;
        $this->resource = $resource;
        $this->createdAt = time();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->extractors);
    }

    /**
     * Checks if the cache needs to be updated or not.
     *
     * @param null|integer $timestamp
     *
     * @return boolean whether cache needs to be updated.
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
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->extractors,
            $this->resource,
            $this->createdAt,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($data)
    {
        list(
            $this->extractors,
            $this->resource,
            $this->createdAt
        ) = unserialize($data);
    }
}
