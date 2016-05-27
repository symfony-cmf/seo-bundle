<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader;

use Symfony\Cmf\Bundle\SeoBundle\Cache\CacheInterface;
use Symfony\Cmf\Bundle\SeoBundle\Cache\ExtractorCollection;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface;
use Symfony\Component\Config\Loader\Loader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class ExtractorLoader extends Loader
{
    /**
     * @var null|CacheInterface
     */
    private $cache;

    /**
     * @var ExtractorInterface[][]
     */
    private $extractors = array();

    /**
     * @param CacheInterface       $cache
     */
    public function __construct(CacheInterface $cache = null)
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
     * @return ExtractorCollection
     */
    private function getExtractorsForContent($content)
    {
        $cachingAvailable = (bool) $this->cache;

        if (!$cachingAvailable) {
            return new ExtractorCollection($this->findExtractorsForContent($content));
        }

        $extractors = $this->cache->loadExtractorsFromCache(get_class($content));

        if (null === $extractors || !$extractors->isFresh()) {
            $extractors = $this->findExtractorsForContent($content);
            $this->cache->putExtractorsInCache(get_class($content), $extractors);
            $extractors = new ExtractorCollection($extractors);
        }

        return $extractors;
    }

    /**
     * Returns the extractors that support the content.
     *
     * @param object $content
     *
     * @return ExtractorInterface[]
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

        return $extractors;
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
