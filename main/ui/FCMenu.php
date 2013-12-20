<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FCMenu
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FCMenu {
    
    public $menu = array();

    public function __construct() {
        $this->menu = get_site_option('_flock_menu_items');
    }
    

}

?>
