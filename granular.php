<?php
/**
 * @version 0.0.8
 */
/*
Plugin Name: Granular
Plugin URI: https://github.com/javanile/granular
Description: WordPress extension framework based on object-oriented paradigm.
Author: Francesco Bianco
Version: 0.0.8
Author URI: https://github.com/javanile
*/

defined('ABSPATH') or exit;

require_once __DIR__.'/vendor/autoload.php';

$app = new Javanile\Granular\Autoload();

$app->autoload('Javanile\\Granular\\', __DIR__.'/src');
