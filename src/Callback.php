<?php

namespace Javanile\WpGranular;

final class Callback
{
    /**
     * @var string
     */
    private $bindClass;

    /**
     * @var object
     */
    private $bindObject;

    /**
     * Callback constructor.
     *
     * @param $bindClass
     * @internal param $class
     * @internal param $method
     */
    public function __construct($bindClass)
    {
        $this->bindClass = $bindClass;
    }

    /**
     *
     */
    private function bindObject()
    {
        if ($this->bindObject == null) {
            $this->bindObject = new $this->bindClass();
        }

        return $this->bindObject;
    }

    /**
     * @param $method
     * @return \Closure
     */
    public function addMethodCallback($method)
    {
        return function () use ($method) {
            call_user_func_array([$this->bindObject(), $method], func_get_args());
        };
    }
}
