<?php

/*
  Plugin Name: Flock Community
  Plugin URI: http://rtcamp.com/buddypress-media/?utm_source=dashboard&utm_medium=plugin&utm_campaign=buddypress-media
  Description: Tools for building a community (not a social network) around your WordPress website
  Version: 0.1
  Author: saurabhshukla
  Text Domain: flock-community
  Author URI: http://rtcamp.com/?utm_source=dashboard&utm_medium=plugin&utm_campaign=buddypress-media
 */


/**
 * Define plugin absolute path and url
 */
if (!defined('FLOCK_PATH')) {
    define('FLOCK_PATH', plugin_dir_path(__FILE__));
}

if (!defined('FLOCK_URL')) {
    define('FLOCK_URL', plugin_dir_url(__FILE__));
}

/**
 * start session, if haven't already
 */

if (!session_id()) {
    session_start();
}

/**
 * Autoload classes
 * 
 * @param string $class_name
 */
function fc_autoloader($class_name) {
    $base_path = 'main/';
    $subpath_array = apply_filters(
            'fc_autoloader_subpath', array(
        'relationships',
        'flocks',
        'circles',
        'groups'
            )
    );

    foreach ($subpath_array as $path) {
        $path = RTMEDIA_PATH . $base_path . '/' . $path . '/' . $class_name;
        if (file_exists($path)) {
            include $path;
            break;
        }
    }
}

/**
 * Register the autoloader function into spl_autoload
 */
spl_autoload_register ( 'fc_autoloader' );

/**
 * Instantiate the Flock Community class.
 */
global $fc;
$fc = new FlockCommunity();


/*
 * Look Ma! Very few includes! Next File: /app/main/RTMedia.php
 */