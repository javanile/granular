<?php

namespace Javanile\WpGranular\Tests;

use Javanile\Granular\Tests\Fixtures\FakeSubBindable;
use PHPUnit\Framework\TestCase;

final class BindableTest extends TestCase
{
    public function testBindable()
    {
        $this->assertTrue(property_exists(FakeSubBindable::class, 'bindings'));
        $this->assertTrue(method_exists(FakeSubBindable::class, 'getBindings'));
    }
}
