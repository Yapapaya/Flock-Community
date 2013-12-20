<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FCFlocksModel
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FCFlocksModel {
    
    public $limit = 10;
    
    public $page = 0;
    

    public function __construct($params=array()) {
        if(is_array($params)){
            extract($params);
        }else{
            return false;
        }
        
    }
    
    public function get() {
        if (!is_array($ids)){
            if(is_int($ids)){
                $ids = array($ids);
            }else{
                return false;
            }
        }
    }
    
    public function add() {
        
    }
    
    public function save($flocks = array()){
        
    }
    
    public function delete($ids = array()){
        
        if (!is_array($ids)){
            if(is_int($ids)){
                $ids = array($ids);
            }else{
                return false;
            }
        }
        
        foreach ($ids as $flock_id){
            //delete_sql
        }
        
    }

}

?>
