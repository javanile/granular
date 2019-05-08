<?php

namespace Javanile\Granular\Tests;

use Javanile\Granular\Callback;
use Javanile\Granular\Tests\Fixtures\FakeContainer;
use Javanile\Granular\Tests\Fixtures\FakeRefClass;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class CallbackTest extends TestCase
{
    public function testCallback()
    {
        $callback = new Callback(FakeRefClass::class, new FakeContainer);

        $getRefObjectMethod = new ReflectionMethod($callback, 'getRefObject');

        $this->assertTrue($getRefObjectMethod->isPrivate());

        $fakeRefMethod = $callback->getMethodCallback('fakeRefMethod');

        $this->assertTrue(is_callable($fakeRefMethod));

        $this->assertEquals($fakeRefMethod(), 'fakeRefMethod');
    }
}
