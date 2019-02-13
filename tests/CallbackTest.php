<?php

namespace Javanile\WpGranular\Tests;

use PHPUnit\Framework\TestCase;
use Javanile\Granular\Callback;
use Javanile\Granular\Tests\Fixtures\FakeRefClass;

final class CallbackTest extends TestCase
{
    public function testCallback()
    {
        $callback = new Callback(FakeRefClass::class);

        $this->assertEquals($callback->getRefObject()->fakeRefMethod(), 'fakeRefMethod');

        $this->assertTrue(is_callable($callback->getMethodCallback()));
    }
}
