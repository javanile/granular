<?php
/**
 * javanile/granular
 *
 * @link      http://github.com/javanile/granular
 * @copyright Copyright (c) 2018-2019 Javanile org.
 * @license   https://github.com/javanile/granular/blob/master/LICENSE
 */

namespace Javanile\Granular;

final class Autoload
{
    /**
     * Override functions.
     *
     * @array
     */
    protected $functions;

    /**
     * Autoload constructor.
     *
     * @param $functions
     */
    public function __construct($functions = null)
    {
        $this->functions = $functions;
    }

    /**
     * Autoload namespace to particular path and look for bindable classess.
     *
     * @param $namespace
     * @param $path
     * @return array
     */
    public function autoload($namespace, $path)
    {
        $autoload = [];
        $namespace = trim($namespace, '\\') . '\\';

        foreach (scandir($path) as $file) {
            if ($file[0] == '.' || !in_array(pathinfo($file, PATHINFO_EXTENSION), ['', 'php'])) {
                continue;
            }

            $dir = $path . '/' . $file;
            if (is_dir($dir)) {
                $autoload = array_merge($autoload, $this->autoload($namespace . $file, $dir));
            }

            $class = $namespace . basename($file, '.php');
            if (!is_subclass_of($class, 'Javanile\\Granular\\Bindable')) {
                continue;
            }

            $autoload[$class] = $this->autoloadBindings($class, $class::getBindings());
        }

        return $autoload;
    }

    /**
     * @param $class
     * @param $bindings
     * @return array
     */
    public function autoloadBindings($class, $bindings)
    {
        $methods = [];
        if (!is_array($bindings)) {
            return $methods;
        }

        $callback = new Callback($class);

        foreach ($bindings as $binding => $method) {
            if (is_number($binding)) {
                $binding = $method;
                $method = null;
            }

            if (!preg_match('/^(action|filter|plugin):([a-z_]+)(:([0-9]+))?(:([0-9]+))?$/', $binding, $tokens)) {
                continue;
            }

            if ($this->addMethodCallback($tokens, $callback, $method)) {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * Assign method callback to trigger.
     *
     * @param $tokens
     * @param $callback
     * @param $method
     * @return mixed|null
     */
    private function addMethodCallback($tokens, Callback $callback, $method = null)
    {
        $method = $method ?: $tokens[2];
        $priority = isset($tokens[4]) ? $tokens[4] : 10;
        $acceptedArgs = isset($tokens[6]) ? $tokens[6] : 1;

        if ($tokens[1] == 'action') {
            $func = isset($this->functions['add_action']) ? $this->functions['add_action'] : 'add_action';
            return call_user_func($func, $tokens[2], $callback->addMethodCallback($method), $priority, $acceptedArgs);
        }

        if ($tokens[1] == 'filter') {
            $func = isset($this->functions['add_filter']) ? $this->functions['add_filter'] : 'add_filter';
            return call_user_func($func, $tokens[2], $callback->addMethodCallback($method), $priority, $acceptedArgs);
        }

        return $this->addMethodCallbackPlugin($tokens, $callback, $method);
    }

    /**
     * Assign method callback to plugin trigger.
     *
     * @param $tokens
     * @param $callback
     * @param $method
     * @return mixed|null
     */
    private function addMethodCallbackPlugin(array $tokens, Callback $callback, $method)
    {
        if ($tokens[1] != 'plugin') {
            return null;
        }

        if (!in_array($tokens[2], ['register_activation_hook', 'register_deactivation_hook'])) {
            return null;
        }

        $func = isset($this->functions[$tokens[2]]) ? $this->functions[$tokens[2]] : $tokens[2];

        call_user_func($func, __FILE__, $callback->addMethodCallback($method));

        return true;
    }
}
