<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FlockCommunity
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FlockCommunity {
    
    public $user = NULL;
    
    public $flock = NULL;
    
    public $relationship = NULL;
    
    public $relation = NULL;

    public function __construct() {
        
    }
    
    public function constants(){
        
    }

    public function init(){
        $this->user = new FCUser();
        $this->relation = new FCRelation();
        $this->flock = new FCFlock();
        $this->relationship = new FCRelationship();    
    }
}
