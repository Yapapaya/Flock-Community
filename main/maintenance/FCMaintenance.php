<?php

/**
 * Runs the maintenance actions on new installations and updates
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FCMaintenance {

    /**
     *
     * @var int Existing plugin version
     */
    private $version = 0;

    /**
     *
     * @var int Existing database version 
     */
    private $db_version = 0;
    
    /**
     * Initialise Maintenance
     */
    public function __construct() {
        
        //setup existing versions
        $this->setup_existing_versions();
        
        //hook before running maintenance
        do_action(FLOCK_COM_PREFIX.'pre_install_update');
        
        //run maintenance
        $this->install_update();
    }
    
    /**
     * Fetch versions stored in options table, will set them to false,
     * if this is first run
     */
    private function setup_existing_versions() {
        $this->version = get_site_option(FLOCK_COM_PREFIX . 'version');
        $this->db_version = get_site_option(FLOCK_COM_PREFIX . 'db_version');
    }
    
    /**
     * 
     */
    private function install_update(){
       
        // if this is the first run or a database update,
        // run the database install/update system
        if($this->is_first_run() || $this->is_db_update()){
            
            // the class which finally runs dbDelta
            $installer = new FCFlockDBInstall();
            $installer->install();
        }
        
        // install the plugin
        $this->install();
        
        // fix indexes
        if(self::is_index_corrupted()){
            self::rebuild_index();
        }
        
        // allow hoooking into update
        do_action(FLOCK_COM_PREFIX.'install_update');
        
        // all done, update version options
        $this->update_version_info();
        
    }

    /**
     * Tries to guess if this is the first time the plugin is installed
     * 
     * @return boolean
     */
    private function is_first_run() {
        if ($this->version && $this->version > 0) {
            return false;
        }
        return true;
    }

    /**
     * Checks if this is an update
     * 
     * @return boolean
     */
    private function is_update() {
        if(version_compare(FLOCK_COM_VERSION, $this->version)===1){
            return true;
        }
        return false;
    }

    /**
     * Checks if it is a database update
     * 
     * @return boolean
     */
    private function is_db_update() {
        if(!$this->is_update()){
            return false;
        }
        if(version_compare(FLOCK_COM_DB_VERSION, $this->db_version)===1){
            return true;
        }
        return false;
    }

    /**
     * Install for the first time
     */
    private function install() {
        if(!$this->is_first_run()){
            return;
        }
        
        // Build the post index
        new FCFlockIndexInstall();
        
        // allow hooking into first run
        do_action(FLOCK_COM_PREFIX.'first_run');
    }

    /**
     * Update versions in the options table
     */
    private function update_version_info() {
        update_site_option(FLOCK_COM_PREFIX . 'version', FLOCK_COM_VERSION);
        update_site_option(FLOCK_COM_PREFIX . 'db_version', FLOCK_COM_DB_VERSION);
    }
    
    static function rebuild_index(){
       do_action(FLOCK_COM_PREFIX.'rebuild_index');
       return; 
    }
    
    static function is_index_corrupted(){
        return false;
    }

}