<?php

namespace Javanile\WpGranular;

final class Callback
{
    /**
     * @var
     */
    private $bindClass;

    /**
     *
     */
    private $bindObject;

    /**
     * Callback constructor.
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
    public function addAction($method)
    {
        return function () use ($method) {
            call_user_func_array([$this->bindObject(), $method], []);
        };
    }

    /**
     * @param $method
     * @return \Closure
     */
    public function addFilter($method)
    {
        return function () use ($method) {
            call_user_func_array([$this->bindObject(), $method], []);
        };
    }
}
