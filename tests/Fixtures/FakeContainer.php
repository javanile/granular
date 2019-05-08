<?php

namespace Javanile\Granular\Tests\Fixtures;

use Psr\Container\ContainerInterface;

class FakeContainer implements ContainerInterface
{
    /**
     * @var array
     */
    protected $functions = [
        'add_action'                 => [FakeFunctions::class, 'alwaysTrue'],
        'add_filter'                 => [FakeFunctions::class, 'alwaysTrue'],
        'add_shortcode'              => [FakeFunctions::class, 'alwaysTrue'],
        'register_activation_hook'   => [FakeFunctions::class, 'alwaysTrue'],
        'register_deactivation_hook' => [FakeFunctions::class, 'alwaysTrue'],
    ];

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->functions[$id]) && $this->functions[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function get($id)
    {
        return $this->functions[$id];
    }
}
