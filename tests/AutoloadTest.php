<?php

namespace Javanile\Granular\Tests;

use Javanile\Granular\Autoload;
use PHPUnit\Framework\TestCase;
use Javanile\Granular\Tests\Fixtures\SubClasses\FakeSubBindable;
use Javanile\Granular\Tests\Fixtures\FakeContainer;

final class AutoloadTest extends TestCase
{
    public function testAutoload()
    {
        $autoload = new Autoload(new FakeContainer);

        $this->assertEquals(
            [FakeSubBindable::class => ['action:init' => ['init']]],
            $autoload->autoload('Javanile\\Granular\\Tests\\Fixtures\\', __DIR__.'/Fixtures')
        );
    }

    public function testBindMethod()
    {
        $autoload = new Autoload(new FakeContainer);

        $this->assertEquals(
            ['action:init' => ['init']],
            $autoload->bind('init')
        );

        $this->assertEquals(
            ['filter:the_content' => ['theContent']],
            $autoload->bind('the_content', 'theContent')
        );
    }

    public function testRegisterClass()
    {
        $autoload = new Autoload(new FakeContainer);

        $this->assertEquals(
            ['action:init' => ['init']],
            $autoload->register(null, ['init'])
        );

        $this->assertEquals(
            ['action:init:0:1' => ['init']],
            $autoload->register(null, ['action:init:0:1' => 'init'])
        );

        $this->assertEquals(
            ['filter:my_filter' => ['myFilterMethod']],
            $autoload->register(null, ['filter:my_filter' => 'myFilterMethod'])
        );

        $this->assertEquals(
            ['shortcode:my_shortcode' => ['myShortcodeMethod']],
            $autoload->register(null, ['shortcode:my_shortcode' => 'myShortcodeMethod'])
        );

        $this->assertEquals(
            [
                'plugin:register_activation_hook'   => ['myRegisterActivationHook'],
                'plugin:register_deactivation_hook' => ['myRegisterDeactivationHook'],
            ],
            $autoload->register(\stdClass::class, [
                'plugin:not_match',
                'not_match:not_match',
                'plugin:register_activation_hook'   => 'myRegisterActivationHook',
                'plugin:register_deactivation_hook' => 'myRegisterDeactivationHook',
            ])
        );
    }
}
