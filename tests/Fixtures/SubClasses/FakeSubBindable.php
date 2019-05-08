<?php

namespace Javanile\Granular\Tests\Fixtures\SubClasses;

use Javanile\Granular\Bindable;

class FakeSubBindable extends Bindable
{
    public static $bindings = [
        'action:init',
    ];
}
