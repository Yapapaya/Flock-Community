<?php

/*
  Plugin Name: Flock Community
  Plugin URI: http://github.com/yapapaya/Flock-Community
  Description: Tools for building a community (not a social network) around your WordPress website
  Version: 0.1
  Author: saurabhshukla
  Text Domain: flock-community
  Author URI: http://profiles.wordpress.org/saurabhshukla/
 */



if (!defined('FLOCK_COM_KEYWORD')) {
    define('FLOCK_COM_KEYWORD', 'flock');
}


/**
 * Define plugin absolute path and url
 */

if (!defined('FLOCK_COM_PATH')) {
    define('FLOCK_COM_PATH', plugin_dir_path(__FILE__));
}

if (!defined('FLOCK_COM_URL')) {
    define('FLOCK_COM_URL', plugin_dir_url(__FILE__));
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
                false,
        'relationships',
        'flocks',
        'circles',
        'groups',
                'users',
                'installation'
            )
    );

    foreach ($subpath_array as $path) {
         if ($path){
            $path = FLOCK_PATH . $base_path . $path . '/' . $class_name . '.php';
        }else{
            $path = FLOCK_PATH . $base_path . $class_name. '.php';
        }
        
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