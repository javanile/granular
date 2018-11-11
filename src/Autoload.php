<?php

namespace Javanile\WpGranular;

final class Autoload
{
    /**
     * @param $namespace
     * @param $path
     */
    public function autoload($namespace, $path)
    {
        foreach (scandir($path) as $file) {
            if ($file[0] == '.' || !in_array(pathinfo($file, PATHINFO_EXTENSION), ['', 'php'])) {
                continue;
            }

            $dir = $path.'/'.$file;
            if (is_dir($dir)) {
                $this->autoload($namespace.$file.'\\', $dir);
            }

            $class = $namespace.basename($file, '.php');
            if (!is_subclass_of($class, 'Javanile\\WpGranular\\Bindable')) {
                continue;
            }

            $this->autoloadClass($class);
        }
    }

    /**
     * @param $class
     */
    public function autoloadClass($class)
    {
        $bindings = $class::getBindings();
        if (!is_array($bindings)) {
            return;
        }

        $callback = new Callback($class);

        foreach ($bindings as $binding => $method) {
            if (!preg_match('/^(action|filter):([a-z_]+)$/', $binding, $tokens)) {
                continue;
            }

            switch ($tokens[1]) {
                case 'action':
                    add_action($tokens[2], $callback->addAction($method));
                    break;
                case 'filter':
                    add_filter($tokens[2], $callback->addFilter($method));
                    break;
            }
        }
    }
}
