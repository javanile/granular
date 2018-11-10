<?php

/**
 * @param $namespace
 * @param $path
 */
function wp_granular_autoload($namespace, $path)
{
    foreach (scandir($path) as $file) {
        if ($file[0] == '.' || !in_array(pathinfo($file, PATHINFO_EXTENSION), ['', 'php'])) {
            continue;
        }

        $dir = $path.'/'.$file;
        if (is_dir($dir)) {
            wp_granular_autoload($namespace.$file.'\\', $dir);
        }

        $class = $namespace.basename($file, '.php');
        if (!is_subclass_of($class, 'Javanile\\WpGranular\\Bindable')) {
            continue;
        }

        if (!isset($class::$bindings) || !is_array($class::$bindings)) {
            continue;
        }

        wp_granular_autoload_class($class);
    }
}

/**
 * @param $class
 */
function wp_granular_autoload_class($class)
{
    foreach ($class::$bindings as $binding => $method) {
        if (!preg_match('/^(action|filter):([a-z]+)$/', $binding, $tokens)) {
            continue;
        }

        $callback = new Javanile\WpGranular\Callback($class, $method);

        switch ($tokens[1]) {
            case 'action':
                add_action($tokens[2], [$callback, 'action']);
                break;
            case 'filter':
                add_filter($tokens[2], [$callback, 'filter']);
                break;
        }
    }
}
