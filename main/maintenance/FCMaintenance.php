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

    public function __construct() {
        $this->setup_existing_versions();
        $this->install_upgrade();
    }
    
    private function install_upgrade(){
        do_action(FLOCK_COM_PREFIX.'install_upgrade');
        
        if($this->is_first_run() || $this->is_db_update()){
            $installer = new FCFlockDBInstall();
            $installer->install();
        }
        
        if($this->is_first_run()){
            $this->install();
        }
        if($this->is_update()){
            $this->update();
        }
        
        
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
        
        new FCFlockIndexInstall();
        
        $this->update_version_info();
    }

    /**
     * Update versions in the options table
     */
    private function update_version_info() {
        update_site_option(FLOCK_COM_PREFIX . 'version', FLOCK_COM_VERSION);
        update_site_option(FLOCK_COM_PREFIX . 'db_version', FLOCK_COM_DB_VERSION);
    }
    
    static function rebuild_index(){
       return; 
    }
    
    static function is_index_corrupted(){
        return false;
    }

}
