<?php

namespace Javanile\WpGranular\Tests;

use PHPUnit\Framework\TestCase;
use Javanile\Granular\Tests\Fixtures\FakeBindable;

final class BindableTest extends TestCase
{
    public function testBindable()
    {
        $this->assertTrue(property_exists(FakeBindable::class, 'bindings'));
        $this->assertTrue(method_exists(FakeBindable::class, 'getBindings'));
    }
}
