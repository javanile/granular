<?php

namespace Javanile\WpGranular;

final class Callback
{
    /**
     * @var
     */
    private $class;

    /**
     * @var
     */
    private $method;

    /**
     *
     */
    private $consumer;

    /**
     * Callback constructor.
     * @param $class
     * @param $method
     */
    public function __construct($class, $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    /**
     *
     */
    private function initConsumer()
    {
        if ($this->consumer == null) {
            $this->consumer = new $this->class();
        }
    }

    /**
     *
     */
    public function action()
    {
        $this->initConsumer();

        $this->consumer->{$this->method}();
    }
}
