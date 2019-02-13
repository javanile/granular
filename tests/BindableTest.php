<?php

namespace Javanile\WpGranular\Tests;

use PHPUnit\Framework\TestCase;
use Javanile\Granular\Tests\Fixtures\FakeSubBindable;

final class BindableTest extends TestCase
{
    public function testBindable()
    {
        $this->assertTrue(property_exists(FakeSubBindable::class, 'bindings'));
        $this->assertTrue(method_exists(FakeSubBindable::class, 'getBindings'));
    }
}
