<?php
/**
 * javanile/granular
 *
 * @link      http://github.com/javanile/granular
 * @copyright Copyright (c) 2018-2019 Javanile org.
 * @license   https://github.com/javanile/granular/blob/master/LICENSE
 */

namespace Javanile\Granular;

abstract class Bindable
{
    /**
     * Default bindings.
     */
    public static $bindings;

    /**
     * Get bindings array.
     *
     * @return array
     */
    public static function getBindings()
    {
        return static::$bindings;
    }
}
