<?php
/**
 * Description of FCAdminBar
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */

class FCAdminBar {

    var $menu = array();

    public function __construct() {
        
        $this->menu = array(
            'fc_add' => __('Add to Circle', 'flock-community'),
            'fc_cir' => __('Add to Group', 'flock-community'),
            'fc_ins' => __('Add to Groups', 'flock-community')
        );
        
    }

}