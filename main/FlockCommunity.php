<?php
// check if loaded directly
if (!defined('ABSPATH')) exit;
// check if a class by the same name exists
if (!class_exists('FlockCommunity')) {
// if only, I could use namespaces (sighs)!
    
    /**
     * The mother class, the global object; initialises everything
     * 
     * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
     */
    class FlockCommunity {
        /**
         *
         * @var object The current user object
         */
        public $user = NULL;
        
        /**
         *
         * @var object The current contextual flock object
         */
        public $flock = NULL;
        
        /**
         * 
         */
        public function __construct() {
            $this->init();
//            $admin_bar = new FCAdminBar;
//            add_action('admin_bar_menu', array($admin_bar, 'init_bar'));
        }
        
        /**
         * Checks if maintenance/upgrades are needed
         * 
         * @return boolean true, if maintenance is needed
         */
        private function needs_maintenance(){
            return false;
        }
        
        /**
         * Checks if plugin is installed for the first time
         * 
         * @return boolean true, if this is the first install
         */
        private function first_install(){
            return false;
        }
        
        /**
         * Initialises functionality
         */
        private function init() {
            $this->load_translation();
        }
        
        /**
         * Loads translation
         */
        private function load_translation() {
            load_plugin_textdomain('flock-community', false, basename(FLOCK_COM_PATH) . '/languages/');
        }

    }

}
/**
 * A haiku, for you my friend:
 * 
 * Outside trickles rain
 * Seeps inside like deep noise
 * Personal yellow stain
 */