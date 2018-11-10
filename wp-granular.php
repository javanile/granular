<?php
/**
 * @package wp-granular
 * @version 0.0.1
 */
/*
Plugin Name: WP Granular
Plugin URI: https://github.com/javanile/wp-granular
Description: WordPress extension framework based on object-oriented paradigm.
Author: Francesco Bianco
Version: 0.0.1
Author URI: https://github.com/javanile
*/

require_once __DIR__.'/src/Bindable.php';
require_once __DIR__.'/src/Callback.php';
require_once __DIR__.'/src/MyFirstAction.php';

require_once __DIR__.'/wp-granular-autoload.php';

wp_granular_autoload('Javanile\\WpGranular\\', __DIR__.'/src');
