<?php

namespace Javanile\WpGranular;

final class Callback
{
    /**
     * @var
     */
    private $class;

    /**
     *
     */
    private $consumer;

    /**
     * Callback constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
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

        $this->consumer->action();
    }
}
