<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FCPostPrivacy
 *
 * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
 */
class FCPostPrivacy {

    public function __construct() {
//        add_filter('posts_join', array($this, 'posts_join'));
//        add_filter('posts_groupby', array($this, 'posts_groupby'));
//        add_filter('posts_where', array($this, 'posts_where'));
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

    private function setup_tables() {
        $this->table_name['flocks'] = $this->table_name('flocks');
        $this->table_name['relationships'] = $this->table_name('relationsips');
        $this->table_name['post_index'] = $this->table_name('post_index');
    }

    public function posts_join($join_array) {

        $default_join = $join_array[0];
        $wp_query_object = $join_array[1];
        $this->setup_tables();



        $join = " INNER JOIN $this->table_name['post_index']" .
                " ON $wpdb->posts.ID = $this->table_name['post_index'].post_id";

        if ($flock_community->user) {
            $join .= " LEFT JOIN $this->table_name['relationships']" .
                    " ON $this->table_name['post_index'].flock_id = $this->table_name['relationships'].flock_id";
        }
        
        return $default_join.$join;
    }

    public function posts_groupby() {

        $this->setup_tables();
        global $flock_community;
    }

    public function posts_where($where_array) {
        $default_where = $where_array[0];
        $wp_query_object = $where_array[1];

        $this->setup_tables();
        global $flock_community;


        $where = " $this->table_name['post_index'].flock_id = 0";

        if ($flock_community->user) {
            $where.=" OR $this->table_name['post_index'].user_id = $flock_community->user->ID";
        }

        $where .= "OR ($this->table_name['post_index'].flock_id ="
                . " $this->table_name['relationships'].flock_id"
                . " AND $this->table_name['relationships'].user_id ="
                . " $flock_community->user->ID
                    AND $this->table_name['relationships'].membership_status ="
                . " 'active')";
        return $default_where.$where;
    }

}
