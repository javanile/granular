<?php

namespace Javanile\Granular\Tests;

use Javanile\Granular\Autoload;
use PHPUnit\Framework\TestCase;

final class AutoloadTest extends TestCase
{
    public function testAutoload()
    {
        $autoload = new Autoload(new FakeContainer());

        $this->assertEquals(
            ['Javanile\\Granular\\Tests\\Fixtures\\FakeBindable' => []],
            $autoload->autoload('Javanile\\Granular\\Tests\\Fixtures\\', __DIR__.'/Fixtures')
        );
    }

    public function testAutoloadBindings()
    {
        $autoload = new Autoload(new FakeContainer());

        $this->assertEquals(['init'], $autoload->autoloadBindings(\stdClass::class, ['init']));

        $this->assertEquals(
            ['init'],
            $autoload->autoloadBindings(\stdClass::class, [
                'action:init:0:1' => 'init',
            ])
        );

        $this->assertEquals(
            ['myFilterMethod'],
            $autoload->autoloadBindings(\stdClass::class, [
                'filter:my_filter' => 'myFilterMethod',
            ])
        );

        $this->assertEquals(
            ['myShortcodeMethod'],
            $autoload->autoloadBindings(\stdClass::class, [
                'shortcode:my_shortcode' => 'myShortcodeMethod'
            ])
        );

        $this->assertEquals(
            ['myRegisterActivationHook', 'myRegisterDeactivationHook'],
            $autoload->autoloadBindings(\stdClass::class, [
                'plugin:notmatch',
                'notmatch:notmatch',
                'plugin:register_activation_hook'   => 'myRegisterActivationHook',
                'plugin:register_deactivation_hook' => 'myRegisterDeactivationHook',
            ])
        );
    }
}
