<?php

function exclude_products_from_search($query)
{
    // Only modify search queries on the frontend main query
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {

        // Get an array of all searchable post types.
        /* $searchable_types = get_post_types(array('exclude_from_search' => false));
        // Unset the one we don't want to be searchable
        unset($searchable_types['client']);

        $query->set(
        'post_type',
        $searchable_types
        ); */

        $current_time = current_time('timestamp');

        // Create a meta query to filter out expired or out of stock products
        $meta_query = array(
            'relation' => 'OR',
            // Non-product posts (pass through untouched)
            array(
                'relation' => 'OR',
                array(
                    'key' => '_stock_status',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key' => 'start_date_unix_timestamp',
                    'compare' => 'NOT EXISTS',
                ),
            ),
            // Products that are both in stock AND not expired
            array(
                'relation' => 'AND',
                array(
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '=',
                ),
                array(
                    'key' => 'start_date_unix_timestamp',
                    'value' => $current_time,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ),
            ),
        );

        // Set the meta query
        $query->set('meta_query', $meta_query);

        // Add custom ordering
        add_filter('posts_orderby', function ($orderby, $query) {
            global $wpdb;
            return "CASE
                    WHEN {$wpdb->posts}.post_type = 'product' AND EXISTS (
                        SELECT 1 FROM {$wpdb->postmeta} pm
                        WHERE pm.post_id = {$wpdb->posts}.ID
                        AND pm.meta_key = 'start_date_unix_timestamp'
                    )
                    THEN 1
                    ELSE 2
                    END ASC,
                    CASE
                    WHEN {$wpdb->posts}.post_type = 'product'
                    THEN (
                        SELECT meta_value
                        FROM {$wpdb->postmeta}
                        WHERE post_id = {$wpdb->posts}.ID
                        AND meta_key = 'start_date_unix_timestamp'
                        LIMIT 1
                    )
                    ELSE {$wpdb->posts}.post_date
                    END ASC";
        }, 10, 2);
    }
    return $query;
}
add_action('pre_get_posts', 'exclude_products_from_search');
