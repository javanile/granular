<?php

namespace Javanile\Granular\Tests;

use Javanile\Granular\Tests\Fixtures\FakeBindable;
use Javanile\Granular\Tests\Fixtures\SubClasses\FakeSubBindable;
use PHPUnit\Framework\TestCase;

final class BindableTest extends TestCase
{
    public function testBindable()
    {
        $this->assertTrue(property_exists(FakeBindable::class, 'bindings'));
        $this->assertTrue(method_exists(FakeBindable::class, 'getBindings'));

        $this->assertTrue(property_exists(FakeSubBindable::class, 'bindings'));
        $this->assertTrue(method_exists(FakeSubBindable::class, 'getBindings'));
    }
}
