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
     *
     * @return array
     */
    public function autoload($namespace, $path)
    {
        $autoload = [];
        $namespace = trim($namespace, '\\').'\\';

        foreach (scandir($path) as $file) {
            if ($file[0] == '.' || !in_array(pathinfo($file, PATHINFO_EXTENSION), ['', 'php'])) {
                continue;
            }

            $dir = $path.'/'.$file;
            if (is_dir($dir)) {
                $autoload = array_merge($autoload, $this->autoload($namespace.$file, $dir));
            }

            $class = $namespace.basename($file, '.php');
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
     *
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
            if (is_numeric($binding)) {
                $binding = $method;
                $method = null;
            }

            $regex = '/^(action|filter|shortcode|plugin):([a-z_]+)(:([0-9]+))?(:([0-9]+))?$/';
            if (!preg_match($regex, $binding, $tokens)) {
                continue;
            }

            $method = $method ?: $tokens[2];
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
     *
     * @return mixed|null
     */
    private function addMethodCallback($tokens, Callback $callback, $method)
    {
        $trigger = isset($tokens[2]) ? $tokens[2] : null;
        $priority = isset($tokens[4]) ? $tokens[4] : 10;
        $acceptedArgs = isset($tokens[6]) ? $tokens[6] : 1;

        if ($tokens[1] == 'action' && $trigger) {
            $func = isset($this->functions['add_action']) ? $this->functions['add_action'] : 'add_action';
            return call_user_func($func, $trigger, $callback->getMethodCallback($method), $priority, $acceptedArgs);
        }

        if ($tokens[1] == 'filter' && $trigger) {
            $func = isset($this->functions['add_filter']) ? $this->functions['add_filter'] : 'add_filter';
            return call_user_func($func, $trigger, $callback->getMethodCallback($method), $priority, $acceptedArgs);
        }

        if ($tokens[1] == 'shortcode') {
            $func = isset($this->functions['add_shortcode']) ? $this->functions['add_shortcode'] : 'add_shortcode';
            return call_user_func($func, $trigger, $callback->getMethodCallback($method));
        }

        return $this->addMethodCallbackPlugin($tokens, $callback, $method);
    }

    /**
     * Assign method callback to plugin trigger.
     *
     * @param $tokens
     * @param $callback
     * @param $method
     *
     * @return mixed|null
     */
    private function addMethodCallbackPlugin(array $tokens, Callback $callback, $method)
    {
        if ($tokens[1] != 'plugin') {
            return;
        }

        $func = isset($tokens[2]) ? $tokens[2] : null;
        if (!in_array($func, ['register_activation_hook', 'register_deactivation_hook'])) {
            return;
        }

        $func = isset($this->functions[$func]) ? $this->functions[$func] : $func;

        call_user_func($func, __FILE__, $callback->getMethodCallback($method));

        return true;
    }
}
