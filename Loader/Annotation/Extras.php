<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Loader\Annotation;

use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadataInterface;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 * @Annotation
 */
class Extras implements SeoMetadataAnnotation
{
    public $key;
    public $type;

    private static $allowedTypesMethodMapping = [
        'property' => 'addExtraProperty',
        'name' => 'addExtraName',
        'http-equiv' => 'addExtraHttp',
    ];

    public function serialize()
    {
        return serialize($this->key);
    }

    public function unserialize($serialized)
    {
        list($this->key) = unserialize($serialized);
    }

    public function configureSeoMetadata(SeoMetadataInterface $seoMetadata, $value)
    {
        if (null === $this->key || null === $this->type) {
            if (!is_array($value)) {
                throw new InvalidArgumentException(
                    'Either set the "type" and "key" options for the @Extras annotation or provide an array with extras.'
                );
            }

            $this->configureAllExtras($seoMetadata, $value);

            return;
        }

        $this->guardTypeAllowed($this->type);

        $seoMetadata->{self::$allowedTypesMethodMapping[$this->type]}($this->key, $value);
    }

    private function configureAllExtras(SeoMetadataInterface $seoMetadata, $value)
    {
        foreach ($value as $type => $extras) {
            $this->guardTypeAllowed($type);

            foreach ($extras as $key => $value) {
                $seoMetadata->{self::$allowedTypesMethodMapping[$type]}($key, $value);
            }
        }
    }

    private function guardTypeAllowed($type)
    {
        if (!isset(self::$allowedTypesMethodMapping[$type])) {
            throw new InvalidArgumentException(sprintf(
                'Extras type "%s" not in the list of allowed ones: "%s".',
                $type,
                implode('", "', array_keys(self::$allowedTypesMethodMapping))
            ));
        }
    }
}
