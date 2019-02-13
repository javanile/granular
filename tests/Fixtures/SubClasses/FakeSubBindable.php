<?php

namespace Javanile\Granular\Tests\Fixtures;

use Javanile\Granular\Bindable;

class FakeSubBindable extends Bindable
{
    static $bindings = [
        'action:init',
    ];
}
