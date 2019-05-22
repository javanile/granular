<?php
/**
 * javanile/granular.
 *
 * @link      http://github.com/javanile/granular
 *
 * @copyright Copyright (c) 2018-2019 Javanile org.
 * @license   https://github.com/javanile/granular/blob/master/LICENSE
 */

namespace Javanile\Granular;

use Psr\Container\ContainerInterface;

final class Callback
{
    /**
     * Referenced class to this instance.
     *
     * @var string
     */
    private $refClass;

    /**
     * Generated object using referenced class.
     *
     * @var object
     */
    private $refObject;

    /**
     *
     */
    private $container;

    /**
     * Callback constructor.
     *
     * @param mixed $refClass
     *
     * @param ContainerInterface $container
     * @internal param $class
     * @internal param $method
     */
    public function __construct($refClass, ContainerInterface $container = null)
    {
        $this->refClass = $refClass;
        $this->container = $container;
    }

    /**
     * Retrieve referenced object.
     */
    private function getRefObject()
    {
        if ($this->container !== null && $this->container->has($this->refClass)) {
            return $this->container->get($this->refClass);
        }

        if ($this->refObject === null) {
            $this->refObject = new $this->refClass();
        }

        return $this->refObject;
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
            return call_user_func_array([$this->getRefObject(), $method], func_get_args());
        };
    }
}
