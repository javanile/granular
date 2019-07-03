<?php
/**
 * javanile/granular.
 *
 * @link      http://github.com/javanile/granular
 *
 * @copyright Copyright (c) 2018-2019 Javanile
 * @license   https://github.com/javanile/granular/blob/master/LICENSE
 */

namespace Javanile\Granular;

use Psr\Container\ContainerInterface;

final class Callback
{
    /**
     * Referenced class to this instance.
     *
     * @var string|object
     */
    private $referer;

    /**
     * Generated object using referenced class.
     *
     * @var object
     */
    private $instance;

    /**
     * DI Container
     */
    private $container;

    /**
     * Callback constructor.
     *
     * @param $referer
     * @param ContainerInterface $container
     */
    public function __construct($referer, ContainerInterface $container = null)
    {
        $this->referer = $referer;
        $this->container = $container;
    }

    /**
     * Retrieve instance by referer or container key.
     */
    private function getInstance()
    {
        if ($this->instance !== null) {
            return $this->instance;
        } elseif (is_object($this->referer)) {
            $this->instance = $this->referer;
        } elseif ($this->container !== null && $this->container->has($this->referer)) {
            $this->instance = $this->container->get($this->referer);
        } else {
            $this->instance = new $this->referer();
        }

        return $this->instance;
    }

    /**
     * Retrieve callback over the object method.
     *
     * @param $method
     *
     * @return \Closure
     */
    public function getMethodCallback($method)
    {
        return function () use ($method) {
            return call_user_func_array([$this->getInstance(), $method], func_get_args());
        };
    }
}
