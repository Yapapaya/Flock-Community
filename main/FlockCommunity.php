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
        $this->init();
        $admin_bar = new FCAdminBar;
        add_action('admin_bar_menu', array($admin_bar, 'init_bar'));
    }

    public function init() {
        $this->load_translation();
        
    }

    public function load_translation() {
        load_plugin_textdomain('flock-community', false, basename(FLOCK_COM_PATH) . '/languages/');
    }

}
