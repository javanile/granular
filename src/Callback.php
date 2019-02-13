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
     * Callback constructor.
     *
     * @param $bindClass
     * @param mixed $refClass
     *
     * @internal param $class
     * @internal param $method
     */
    public function __construct($refClass)
    {
        $this->refClass = $refClass;
    }

    /**
     * Retrieve referenced object.
     */
    private function getRefObject()
    {
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
