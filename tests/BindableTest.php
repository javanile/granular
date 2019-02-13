<?php

namespace Javanile\WpGranular\Tests;

use Javanile\Granular\Tests\Fixtures\FakeBindable;
use PHPUnit\Framework\TestCase;

final class BindableTest extends TestCase
{
    public function testBindable()
    {
        $this->assertTrue(property_exists(FakeBindable::class, 'bindings'));
        $this->assertTrue(method_exists(FakeBindable::class, 'getBinddings'));
    }
}
