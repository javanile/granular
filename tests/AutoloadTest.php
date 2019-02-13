<?php

namespace Javanile\Granular\Tests;

use PHPUnit\Framework\TestCase;
use Javanile\Granular\Autoload;

final class AutoloadTest extends TestCase
{
    public function testAutoload()
    {
        $autoload = new Autoload([
            'add_action' => [ TestUtil::class, 'alwaysTrue' ],
            'add_filter' => [ TestUtil::class, 'alwaysTrue' ],
            'register_activation_hook' => [ TestUtil::class, 'alwaysTrue'],
            'register_deactivation_hook' => [ TestUtil::class, 'alwaysTrue' ],
        ]);

        $this->assertEquals(
            [ 'Javanile\\Granular\\Tests\\Fixtures\\FakeBindable' => [] ],
            $autoload->autoload('Javanile\\Granular\\Tests\\Fixtures\\', __DIR__.'/Fixtures')
        );

        $this->assertEquals(
            [ 'init' ],
            $autoload->autoloadBindings(\stdClass::class, [
                'action:init:0:1' => 'init'
            ])
        );
    }
}
