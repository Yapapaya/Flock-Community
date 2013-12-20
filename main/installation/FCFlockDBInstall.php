<?php
/**
 * Installs and upgrades the database tables for flock community.
 * Creates a table each for a particular flock and the associated relationships
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FCFlockDBInstall {   

    public function __construct() {        
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
        return $wpdb->base_prefix . '_flock_community_' . $suffix;
    }
        
    /**
     * Installs database tables
     * 
     * @return boolean true, if the tables were installed successfully
     */
    public function install() {
        
        // First, get the table name
        $table_name = $this->table_name('flocks');
        
        // Install both tables
        $flock_installed = $this->install_flock($table_name);
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
    private function install_flock($table_name) {
        
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
        $table_name = $this->table_name('relationships');
        
        // get the schema
        $schema = $this->relationship_schema($table_name);
        
        // Get the upgrade file containing dbDelta function
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        // Install the db
        return dbDelta( $schema );
    }
    
    private function index_install() {
        
        // get the table name
        $table_name = $this->table_name('post_index');
        
        // get the schema
        $schema = $this->index_schema($table_name);
        
        // Get the upgrade file containing dbDelta function
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        // Install the db
        return dbDelta( $schema );
    }
    
    private function index_schema($table_name){
        return "CREATE TABLE {$table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            author_id bigint(20) NOT NULL,
            flock_id bigint(20) NOT NULL DEFAULT 0,
            flock_type varchar(100) NULL DEFAULT NULL,
            blog_id bigint(20) NOT NULL,
            date_created datetime NULL DEFAULT NULL,
            PRIMARY KEY  (id),
            KEY flock_id (flock_id),
            KEY user_id (user_id),
            KEY flock_type (flock_type),
            KEY owner_flock_type (owner_id,flock_type, membership_status),
            FOREIGN KEY (flock_id)
                REFERENCES {$this->table_name('$this->flock')}(flock_id),
            FOREIGN KEY (flock_type)
                REFERENCES {$this->table_name($this->flock)}(flock_type),
            FOREIGN KEY (author_id)
                REFERENCES {$this->table_name('users')}(ID)
        );";
    }    
    
    
    private function flock_schema($table_name){
        return "CREATE TABLE {$table_name} (
            flock_id bigint(20) NOT NULL AUTO_INCREMENT ,
            flock_name text,
            flock_slug text,
            flock_parent bigint(20) NULL DEFAULT NULL,
            flock_type varchar(100) NULL DEFAULT NULL,
            date_created datetime NULL DEFAULT NULL,
            date_expires datetime NULL DEFAULT NULL,
            date_deleted datetime NULL DEFAULT NULL,
            creator_id bigint(20) NULL DEFAULT NULL,
            owner_id bigint(20) NULL DEFAULT NULL,
            visibility bigint(20) DEFAULT 0,
            PRIMARY KEY  (flock_id),
            KEY flock_parent (flock_parent),
            KEY flock_slug (flock_slug),
            KEY flock_type (flock_type, visibility ),
            KEY owner_flock_type (owner_id,flock_type),
            KEY owner_flock_children (owner_id, flock_parent),
            FOREIGN KEY (owner_id)
                REFERENCES {$this->table_name('users')}(ID)
        );";
    }

    
    private function relationship_schema($table_name){
        return "CREATE TABLE {$table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT ,
            user_id bigint(20) NOT NULL,
            membership_status varchar(100) NULL DEFAULT NULL,
            flock_id bigint(20) NOT NULL,
            flock_type varchar(100) NULL DEFAULT NULL,
            date_requested datetime NULL DEFAULT NULL,
            date_created datetime NULL DEFAULT NULL,
            date_deleted datetime NULL DEFAULT NULL,
            PRIMARY KEY  (id),
            KEY flock_id (flock_id),
            KEY user_id (user_id),
            KEY flock_type (flock_type),
            KEY owner_flock_type (owner_id,flock_type, membership_status),
            FOREIGN KEY (flock_id)
                REFERENCES {$this->table_name($this->flock)}(flock_id),
            FOREIGN KEY (flock_type)
                REFERENCES {$this->table_name($this->flock)}(flock_type),
            FOREIGN KEY (user_id)
                REFERENCES {$this->table_name('users')}(ID)
        );";
    }
}