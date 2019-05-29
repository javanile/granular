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

class Autoload
{
    /**
     * Override functions.
     *
     * @array
     */
    protected $container;

    /**
     * Autoload constructor.
     *
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Autoload namespace to particular path and look for bindable classes.
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
                continue;
            }

            $class = $namespace.basename($file, '.php');
            if (!is_subclass_of($class, 'Javanile\\Granular\\Bindable')) {
                continue;
            }

            $bindings = $this->registerClass($class);
            if ($bindings) {
                $autoload[$class] = $bindings;
            }
        }

        return $autoload;
    }

    /**
     * Register Class and specific method bindings.
     *
     * @param $class
     * @param $bindings
     *
     * @return array
     */
    public function registerClass($class, $bindings = null)
    {
        if ($bindings === null) {
            $bindings = $class::getBindings();
        }

        if (!is_array($bindings) && $bindings) {
            $bindings = [$bindings];
        }

        $correctBindings = [];
        $callback = new Callback($class, $this->container);

        foreach ($bindings as $binding => $methods) {
            if (is_numeric($binding)) {
                $binding = $methods;
                $methods = null;
            }

            if (!is_array($methods) && $methods) {
                $methods = [$methods];
            }

            if (preg_match('/^the_[a-z_]+$/', $binding) && $binding != 'the_post') {
                $binding = 'filter:'.$binding;
            } elseif (preg_match('/^[a-z_]+$/', $binding)) {
                $binding = 'action:'.$binding;
            }

            $regex = '/^(action|filter|shortcode|plugin):([a-z_]+)(:([0-9]+))?(:([0-9]+))?$/';
            if (!preg_match($regex, $binding, $tokens)) {
                continue;
            }

            $methods = $methods ?: [$tokens[2]];
            foreach ($methods as $method) {
                if ($this->addMethodCallback($tokens, $callback, $method)) {
                    $correctBindings[$binding][] = $method;
                }
            }
        }

        return $correctBindings;
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
            return call_user_func_array(
                $this->getFunction('add_action'),
                [$trigger, $callback->getMethodCallback($method), $priority, $acceptedArgs]
            );
        }

        if ($tokens[1] == 'filter' && $trigger) {
            return call_user_func_array(
                $this->getFunction('add_filter'),
                [$trigger, $callback->getMethodCallback($method), $priority, $acceptedArgs]
            );
        }

        if ($tokens[1] == 'shortcode') {
            return call_user_func_array(
                $this->getFunction('add_shortcode'),
                [$trigger, $callback->getMethodCallback($method)]
            );
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

        $function = isset($tokens[2]) ? $tokens[2] : null;
        if (!in_array($function, ['register_activation_hook', 'register_deactivation_hook'])) {
            return;
        }

        call_user_func_array($this->getFunction($function), [__FILE__, $callback->getMethodCallback($method)]);

        return true;
    }

    /**
     * Get function from DI container.
     *
     * @param $function
     * @return mixed
     */
    private function getFunction($function)
    {
        if ($this->container === null || !$this->container->has($function)) {
            return $function;
        }

        return $this->container->get($function);
    }
}
