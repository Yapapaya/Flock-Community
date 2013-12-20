<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FCFlock
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FCFlock {

    public function __construct() {
        
    }
    
    /**
     * 
     * @param array $params Parameters for registering a new flock
     * $parameters = array(
     *  'name'          => 'circle',
     *  'plural_name'   => 'circles',
     *  'is_one_way'    => 'true',
     *  'membership'    => 'private', // 'invitation', 'request'
     *  'labels'        => array(
     *  ),
     *  
     * @param boolean $is_separate_table
     * @return boolean
     */
    public function register_flock($params=array(),$is_separate_table=false){
        
        if(empty($params)){
           return false; 
        }
        
        extract($params);
        
        if($is_separate_table){
            $installer = new FCFlockDBInstall($name);
            $installer->install();
        }
        
    }

}

?>
