<?php

namespace Javanile\Granular\Tests\Fixtures;

use Javanile\Granular\Bindable;

class FakeSubBindable extends Bindable
{
    public static $bindings = [
        'action:init',
    ];
}
