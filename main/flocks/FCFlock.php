<?php

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
     * @return boolean
     */
    public function register($params = array()) {

        if (empty($params)) {
            return false;
        }

        extract($params);
    }

    /**
     * 
     */
    public function register_defaults() {

        $params[] = array(
            'name' => 'circle',
            'plural_name' => 'circles',
            'membership' => 'private',
            'notification_direction' => 'to'
        );

        $params[] = array(
            'name' => 'system_circle',
            'plural_name' => 'system_circles',
            'membership' => 'auto',
            'notification_direction' => false
        );

        $params[] = array(
            'name' => 'group',
            'plural_name' => 'groups',
            'membership' => 'request',
            'notification_direction' => 'tofro'
        );

        /**
         * 
          $params[] = array(
          'name' => 'interest',
          'plural_name' => 'interests',
          'membership' => 'auto',
          'notification_direction'   => false
          );

          $params[] = array(
          'name' => 'event',
          'plural_name' => 'events',
          'membership' => 'request',
          'notification_direction'   => 'tofro'
          );
         * 
         */
        $params = apply_filters(FLOCK_COM_PREFIX . 'register_flock', $params);

        foreach ($params as $param) {
            $this->register($param);
        }
    }

}
