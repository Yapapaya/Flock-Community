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

/**
 * Default file, contains Plugin Meta data for WordPress
 * Some constants and autoloading mechanism
 * Initialises the application (plugin)
 */

/**
 * Versioning Information
 */

if (!defined('FLOCK_COM_VERSION')) {
    define('FLOCK_COM_VERSION', 0.1);
}

if (!defined('FLOCK_COM_DB_VERSION')) {
    define('FLOCK_COM_DB_VERSION', 1);
}

/**
 * Prefix for options and meta stuff
 */
if (!defined('FLOCK_COM_PREFIX')) {
    define('FLOCK_COM_PREFIX', '_flock_com_');
}


/**
 * Debug Mode
 */

if (!defined('FLOCK_COM_DEBUG')) {
    // define it as WordPress's debug mode, by default
    define('FLOCK_COM_DEBUG', WP_DEBUG);
}

/**
 * Define plugin absolute path and url for ease of use
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
 * @param string $class_name The name of the class
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
                'installation',
                'maintenance',
                'ui'
            )
    );

    foreach ($subpath_array as $path) {
         if ($path){
            $path = FLOCK_COM_PATH . $base_path . $path . '/' . $class_name . '.php';
        }else{
            $path = FLOCK_COM_PATH . $base_path . $class_name. '.php';
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
 * Initialize the application.
 */
global $flock_community;
$flock_community = new FlockCommunity();