<?php

namespace Javanile\WpGranular;

abstract class Bindable
{
    public static function getBindings()
    {
        return static::$bindings;
    }
}
