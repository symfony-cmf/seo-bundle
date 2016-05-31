<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader;

use Doctrine\Common\Annotations\Reader;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Cmf\Bundle\SeoBundle\Cache\CachedCollection;
use Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation\SeoMetadataAnnotation;
use Symfony\Component\Config\Loader\Loader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class AnnotationLoader extends Loader
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var null|CacheItemPoolInterface
     */
    private $cache;

    public function __construct(Reader $reader, CacheItemPoolInterface $cache = null)
    {
        $this->reader = $reader;
        $this->cache = $cache;
    }

    public function supports($resource, $type = null)
    {
        return is_object($resource) && $this->isAnnotated($resource);
    }

    public function load($content, $type = null)
    {
        $seoMetadata = SeoMetadataFactory::initializeSeoMetadata($content);

        $data = $this->getAnnotationForContent($content);

        $classReflection = new \ReflectionClass($content);
        foreach ($data['properties'] as $propertyName => $annotations) {
            /** @var SeoMetadataAnnotation $annotation */
            foreach ($annotations as $annotation) {
                $property = $classReflection->getProperty($propertyName);
                $property->setAccessible(true);

                $annotation->configureSeoMetadata($seoMetadata, $property->getValue($content));
            }
        }

        foreach ($data['methods'] as $methodName => $annotations) {
            /** @var SeoMetadataAnnotation $annotation */
            foreach ($annotations as $annotation) {
                $annotation->configureSeoMetadata($seoMetadata, $content->{$methodName}());
            }
        }

        return $seoMetadata;
    }

    private function getAnnotationForContent($content)
    {
        $cachingAvailable = (bool) $this->cache;

        if (!$cachingAvailable) {
            return $this->readAnnotations($content);
        }

        $annotationsItem = $this->cache->getItem(CachedCollection::generateCacheItemKey('annotations', get_class($content)));

        if (!$annotationsItem->isHit() || !$annotationsItem->get()->isFresh()) {
            $annotationsItem->set(CachedCollection::createFromObject($content, $this->readAnnotations($content)));

            $this->cache->save($annotationsItem);
        }

        return $annotationsItem->get()->getData();
    }

    private function readAnnotations($content)
    {
        $classReflection = new \ReflectionClass($content);

        return [
            'properties' => $this->readProperties($classReflection->getProperties()),
            'methods'    => $this->readMethods($classReflection->getMethods()),
        ];
    }

    /**
     * @param \ReflectionProperty[] $properties
     *
     * @return SeoMetadataAnnotation[][]
     */
    private function readProperties(array $properties)
    {
        $propertyAnnotations = [];

        foreach ($properties as $reflectionProperty) {
            $annotations = $this->reader->getPropertyAnnotations($reflectionProperty);
            $propertyName = $reflectionProperty->getName();

            foreach ($annotations as $annotation) {
                if ($annotation instanceof SeoMetadataAnnotation) {
                    if (!isset($propertyAnnotations[$propertyName])) {
                        $propertyAnnotations[$propertyName] = [];
                    }

                    $propertyAnnotations[$propertyName][] = $annotation;
                }
            }
        }

        return $propertyAnnotations;
    }

    /**
     * @param \ReflectionMethod[] $methods
     *
     * @return SeoMetadataAnnotation[][]
     */
    private function readMethods(array $methods)
    {
        $methodAnnotations = [];

        foreach ($methods as $reflectionMethod) {
            $annotations = $this->reader->getMethodAnnotations($reflectionMethod);
            $methodName = $reflectionMethod->getName();

            foreach ($annotations as $annotation) {
                if ($annotation instanceof SeoMetadataAnnotation) {
                    if (!isset($methodAnnotations[$methodName])) {
                        $methodAnnotations[$methodName] = [];
                    }

                    $methodAnnotations[$methodName][] = $annotation;
                }
            }
        }

        return $methodAnnotations;
    }

    private function isAnnotated($content)
    {
        return 0 !== count(call_user_func_array('array_merge', $this->readAnnotationsFromContent($content)));
    }
}
