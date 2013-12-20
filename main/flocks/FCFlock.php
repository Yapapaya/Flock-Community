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
     *  'notification_is_one_way'   => 'true',
     *  ),
     *  
     * @return boolean
     */
    public function register($params=array()){
        
        if(empty($params)){
           return false; 
        }
        
        extract($params);
        
    }
    
    public function flock(){
        
    }

}

?>
