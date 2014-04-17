<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;

/**
 * A simple value object for storing key-value pairs of
 * arbitrary metadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class ExtraProperty
{
    /**
     * The key for an arbitrary property.
     *
     * @var string
     */
    private $key;

    /**
     * The value of an arbitrary property.
     *
     * @var string
     */
    private $value;

    /**
     * Contains one of the following values:
     *  - http-equiv
     *  - name
     *  - property
     * as the type for the meta information.
     *
     * @var string
     */
    private $type;

    /**
     * An array to store the possible values for the meta types.
     *
     * @var array
     */
    private static $allowedTypes = array('name', 'property', 'http-equiv');

    public function __construct($key, $value, $type)
    {
        $this->key = $key;
        $this->value = $value;

        if (!in_array($type, self::$allowedTypes)) {
            throw new InvalidArgumentException(sprintf('Type "%s" is not allowed for meta tags, use one of: %s', $type, implode(', ', self::$allowedTypes)));
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public static function getAllowedTypes()
    {
        return self::$allowedTypes;
    }
}
