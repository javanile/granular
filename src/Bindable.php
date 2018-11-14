<?php

namespace Javanile\WpGranular;

abstract class Bindable
{
    /**
     *
     */
    public static $bindings;

    /**
     * @return array
     */
    public static function getBindings()
    {
        return static::$bindings;
    }
}
