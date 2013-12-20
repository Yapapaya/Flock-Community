<?php

// check if loaded directly
if (!defined('ABSPATH'))
    exit;
// check if a class by the same name exists
if (!class_exists('FCPostPrivacy')) {

    /**
     * Filters the SQL statement for WP Query
     *
     * @author Saurabh Shukla <contact.saurabhshukla@gmail.com>
     */
    class FCPostPrivacy {

        public function __construct() {
            add_filter('posts_join', array($this, 'posts_join'));
            add_filter('posts_groupby', array($this, 'posts_groupby'));
            add_filter('posts_where', array($this, 'posts_where'));
        }

        /**
         * Adds the appropriate WordPress database prefix.
         * Returns the complete table name for a given suffix.
         * 
         * @global object $wpdb
         * @param string $suffix The suffix for the table
         * @return string
         */
        private function table_name($suffix) {
            global $wpdb;
            return $wpdb->base_prefix . '_flock_community_' . $suffix;
        }

        /**
         * Sets up the table names for each table
         */
        private function setup_tables() {
            $this->table_name['flocks'] = $this->table_name('flocks');
            $this->table_name['relationships'] = $this->table_name('relationships');
            $this->table_name['post_index'] = $this->table_name('post_index');
        }

        /**
         * Joins the Flock tables to the SQL query
         * 
         * @global object $wpdb
         * @param array $join_array An array containing the default join clause and the query object 
         * @return string
         */
        public function posts_join($join_array) {
            global $wpdb;

            // the default join clause
            $join = $join_array[0];

            // set up table names
            $this->setup_tables();

            // left join the post index table,
            $join .= " LEFT JOIN $this->table_name['post_index'] ON $wpdb->posts.ID = $this->table_name['post_index'].post_id";

            // left join the relationships and the flocks table, only if the user is logged in
            // for a visitor, there're no entries in the table
            // and it is useless then
            if (is_user_logged_in()) {
                $join .= " LEFT JOIN $this->table_name['flocks] ON $this->table_name['post_index'].flock_id = $this->table_name['flocks].flock_parent"
                        . " LEFT JOIN $this->table_name['relationships'] ON $this->table_name['post_index'].flock_id = $this->table_name['relationships'].flock_id";
            }
            return $join;
        }

        /**
         * Forces GROUP BY clause
         * 
         * @global object $wpdb
         * @return string
         */
        public function posts_groupby() {

            global $wpdb;

            // group results by post ID
            $groupby = "{$wpdb->posts}.ID";

            return $groupby;
        }

        /**
         * Modifies the WHERE clause for flock based visibility
         * 
         * @global object $wpdb 
         * @global object $flock_community
         * @param array $where_array An array containing the default where clause and the query object
         * @return string
         */
        public function posts_where($where_array) {

            // the default where clause
            $where = $where_array[0];

            // set up the table names
            $this->setup_tables();

            global $flock_community;
            global $wpdb;

            // posts that are not indexed
            $where .= " AND ( $this->table_name['post_index'].post_id IS NULL "
                    // posts that are public
                    . "OR $this->table_name['post_index'].flock_id = 0";

            // if the user is logged in
            if (is_user_logged_in()) {
                $user = wp_get_current_user();

                // posts that are visble to all logged in users
                $where.=" OR $this->table_name['post_index'].flock_id =1"
                        // posts that were shared only with the current user
                        . " OR $this->table_name['post_index'].user_id = $user->ID"
                        // or his own authored posts
                        . " OR $wpdb->posts.post_author = $user->ID "
                        // or the user is an active member of the flock that the post is meant for
                        . " OR (($this->table_name['post_index'].flock_id = $this->table_name['relationships'].flock_id"
                                // or this flock's parents.
                                // Everything of the parent is meant for the child,
                                // nothing of the child is meant for the parent
                                . " OR $this->table_name['flocks'].flock_parent = $this->table_name['relationships'].flock_id)"
                            . " AND $this->table_name['relationships'].user_id = $user->ID
                            AND $this->table_name['relationships'].membership_status ='active')";
            }

            $where .= ")";

            // go ahead, read that where clause again!

            return $where;
        }

    }

}