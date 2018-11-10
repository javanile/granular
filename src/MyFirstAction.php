<?php

namespace Javanile\WpGranular;

class MyFirstAction implements Bindable
{
    /**
     * @var array
     */
    public static $bindings = [
        'action:init' => 'action'
    ];

    /**
     *
     */
    public function action()
    {
    }
}
