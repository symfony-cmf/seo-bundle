<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader;

use Doctrine\Common\Annotations\Reader;
use Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation\MetaDescription;
use Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation\MetaKeywords;
use Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation\SeoMetadataAnnotation;
use Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation\Title;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;
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

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function supports($resource, $type = null)
    {
        return is_object($resource) && $this->isAnnotated($resource);
    }

    public function load($content, $type = null)
    {
        $seoMetadata = SeoMetadataFactory::initializeSeoMetadata($content);

        $data = $this->getAnnotationsFromContent($content);

        // todo cache $data

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

    private function getAnnotationsFromContent($content)
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
        return 0 !== count(call_user_func_array('array_merge', $this->getAnnotationsFromContent($content)));
    }
}
