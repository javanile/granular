<?php

namespace Javanile\Granular\Tests\Fixtures;

use Javanile\Granular\Bindable;

class FakeBindable extends Bindable
{
    public static $bindings = [
        'notmatch:notmatch',
    ];
}
