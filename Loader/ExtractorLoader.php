<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Cmf\Bundle\SeoBundle\Cache\CachedCollection;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface;
use Symfony\Component\Config\Loader\Loader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class ExtractorLoader extends Loader
{
    /**
     * @var null|CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var ExtractorInterface[][]
     */
    private $extractors = array();

    /**
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(CacheItemPoolInterface $cache = null)
    {
        $this->cache = $cache;
    }

    /**
     * Add an extractor for SEO metadata.
     *
     * @param ExtractorInterface $extractor
     * @param int                $priority
     */
    public function addExtractor(ExtractorInterface $extractor, $priority = 0)
    {
        if (!isset($this->extractors[$priority])) {
            $this->extractors[$priority] = array();
        }
        $this->extractors[$priority][] = $extractor;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_object($resource) && $this->containsExtractors($resource);
    }

    /**
     * {@inheritdoc}
     *
     * @param object $content
     */
    public function load($content, $type = null)
    {
        $seoMetadata = SeoMetadataFactory::initializeSeoMetadata($content);

        $extractors = $this->getExtractorsForContent($content);

        foreach ($extractors as $extractor) {
            $extractor->updateMetadata($content, $seoMetadata);
        }

        return $seoMetadata;
    }

    /**
     * Returns and caches the extractors for content.
     *
     * @param object $content
     *
     * @return CachedCollection
     */
    private function getExtractorsForContent($content)
    {
        $cachingAvailable = (bool) $this->cache;

        if (!$cachingAvailable) {
            return $this->findExtractorsForContent($content);
        }

        $extractorsItem = $this->cache->getItem(
            CachedCollection::generateCacheItemKey('extractors', get_class($content))
        );

        // regenerate cache if needed
        if (!$extractorsItem->isHit() || !$extractorsItem->get()->isFresh()) {
            $extractorsItem->set($this->findExtractorsForContent($content));

            $this->cache->save($extractorsItem);
        }

        return $extractorsItem->get();
    }

    /**
     * Returns the extractors that support the content.
     *
     * @param object $content
     *
     * @return CachedCollection
     */
    private function findExtractorsForContent($content)
    {
        $extractors = array();
        ksort($this->extractors);
        foreach ($this->extractors as $priority) {
            $supportedExtractors = array_filter($priority, function (ExtractorInterface $extractor) use ($content) {
                return $extractor->supports($content);
            });

            $extractors = array_merge($extractors, $supportedExtractors);
        }

        return CachedCollection::createFromObject($content, $extractors);
    }

    /**
     * Whether there are extractors supporting the content.
     *
     * @param object $content
     *
     * @return bool
     */
    private function containsExtractors($content)
    {
        return 0 !== count(iterator_to_array($this->getExtractorsForContent($content)));
    }
}
