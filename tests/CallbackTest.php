<?php

namespace Javanile\WpGranular\Tests;

use Javanile\Granular\Callback;
use Javanile\Granular\Tests\Fixtures\FakeRefClass;
use PHPUnit\Framework\TestCase;

final class CallbackTest extends TestCase
{
    public function testCallback()
    {
        $callback = new Callback(FakeRefClass::class);

        $this->assertEquals($callback->getRefObject()->fakeRefMethod(), 'fakeRefMethod');

        $this->assertTrue(is_callable($callback->getMethodCallback()));
    }
}
