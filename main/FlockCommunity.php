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
         * Initialises functionality
         */
        private function init() {
            
            // load translation
            $this->load_translation();
            
            // Run maintenance system
            new FCMaintenance();
            
            if(is_user_logged_in()){
                $this->user = wp_get_current_user();
            }
            
            $this->setup_environment();
            
        }
        
        /**
         * Loads translation
         */
        private function load_translation() {
            load_plugin_textdomain('flock-community', false, basename(FLOCK_COM_PATH) . '/languages/');
        }
        
        private function setup_environment(){
           
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