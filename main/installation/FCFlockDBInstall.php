<?php
/**
 * Installs the database tables for a flock.
 * Creates a table each for a particular flock and the associated relationships
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FCFlockDBInstall {    
    /**
     *
     * @var string The name of the flock. For e.g., 'circles' 
     */
    var $flock = FLOCK_KEYWORD;

    /**
     * 
     * @global object $wpdb
     * @param string $flock The name of the flock. For e.g., 'groups'
     */
    public function __construct($flock=false) {
        
        // Set the flock name to the one provided
        if($flock){
            $this->flock = $flock;
        }
        
    }
    
    /**
     * Adds the appropriate WordPress database prefix.
     * Returns the complete table name for a given suffix.
     * 
     * @global object $wpdb The global WordPress database object
     * @param string $suffix The suffix for the table
     * @return string The complete table name
     */
    private function table_name($suffix) {
        global $wpdb;
        return $wpdb->base_prefix . '_' . $suffix;
    }
    
    /**
     * Checks if a table exists
     * 
     * @global object $wpdb The global WordPress database object
     * @param string $table_name Complete name of the table
     * @return boolean True if the table already exists
     */
    private function table_exists($table_name) {
        
        global $wpdb;
        
        if ($wpdb->query("SHOW TABLES LIKE '" . $table_name . "'") == 1) {
            return true;
        }

        return false;
    
        }
        
    /**
     * Installs database tables
     * 
     * @return boolean true, if the tables were installed successfully
     */
    public function install() {
        
        // First, get the table name
        $table_name = $this->table_name($this->flock);
        
        // Return early, if the table already exists
        if ($this->table_exists($table_name)) {
            return false;
        }
        
        // Install both tables
        $flock_installed = $this->install_flock();
        $relationship_installed = $this->install_flock_relationship();
        
        // Were both the tables installed successfully?
        if(flock_installed===true && $relationship_installed===true){
            return true;
        }
        
        // Oops! Nothing was installed
        return false;
        
    }
    
    /**
     * Install the flock table
     */
    private function install_flock() {
        
        // Get the database schema
        $schema = $this->flock_schema($table_name);
        
        // Get the upgrade file containing dbDelta function
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        // Install the db
        return dbDelta( $schema );
        
    }
    
    /**
     * Install the relationship table
     * 
     * @return boolean False, if the table already exists
     */
    private function install_flock_relationship() {
        
        // get the table name
        $table_name = $this->table_name("{$this->flock}_relationships");
        
        // check if it doesn't exist already
        if ($this->table_exists($table_name)) {
            return false;
        }
        
        // get the schema
        $schema = $this->relationship_schema($table_name);
        
        // Get the upgrade file containing dbDelta function
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        // Install the db
        return dbDelta( $schema );
    }
    
    private function flock_schema($table_name){
        return "CREATE TABLE {$table_name} (
            {$this->flock}_id bigint(20) NOT NULL AUTO_INCREMENT ,
            {$this->flock}_name text,
            {$this->flock}_slug text,
            {$this->flock}_parent bigint(20) NOT NULL,
            {$this->flock}_type varchar(100) NULL DEFAULT NULL,
            date_created datetime NULL DEFAULT NULL,
            date_expires datetime NULL DEFAULT NULL,
            date_deleted datetime NULL DEFAULT NULL,
            creator_id bigint(20) NULL DEFAULT NULL,
            owner_id bigint(20) NULL DEFAULT NULL,
            PRIMARY KEY  ({$this->flock}_id),
            KEY {$this->flock}_parent ({$this->flock}_parent),
            KEY {$this->flock}_slug ({$this->flock}_slug),
            KEY {$this->flock}_type ({$this->flock}_type),
            KEY owner_{$this->flock}_type (owner_id,{$this->flock}_type),
            KEY owner_{$this->flock}_children (owner_id, {$this->flock}_parent),
            FOREIGN KEY (owner_id)
                REFERENCES {$this->table_name('users')}(ID)
        );";
    }

    
    private function relationship_schema($table_name){
        return "CREATE TABLE {$table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT ,
            user_id bigint(20) NOT NULL,
            membership_status varchar(100) NULL DEFAULT NULL,
            {$this->flock}_id bigint(20) NOT NULL,
            {$this->flock}_type varchar(100) NULL DEFAULT NULL,
            date_requested datetime NULL DEFAULT NULL,
            date_created datetime NULL DEFAULT NULL,
            date_deleted datetime NULL DEFAULT NULL,
            PRIMARY KEY  (id),
            KEY {$this->flock}_id ({$this->flock}_id),
            KEY user_id (user_id),
            KEY {$this->flock}_type ({$this->flock}_type),
            KEY owner_{$this->flock}_type (owner_id,{$this->flock}_type, membership_status),
            FOREIGN KEY ({$this->flock}_id)
                REFERENCES {$this->table_name($this->flock)}({$this->flock}_id),
            FOREIGN KEY ({$this->flock}_type)
                REFERENCES {$this->table_name($this->flock)}({$this->flock}_type),
            FOREIGN KEY (user_id)
                REFERENCES {$this->table_name('users')}(ID)
        );";
    }
}