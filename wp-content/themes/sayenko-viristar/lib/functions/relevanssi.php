<?php

// Rellevanssi

/* add_filter('relevanssi_comparison_order', function ($comparison_order) {
$comparison_order['post_type'] = array('product', 'page', 'post');
return $comparison_order;
});
 */

add_filter('relevanssi_modify_wp_query', function ($query) {
    if (!is_admin() && $query->is_search()) {
        $query->set('orderby', array('post_type' => 'ASC', 'relevance' => 'DESC'));
    }
    return $query;
});

add_filter('relevanssi_comparison_order', function ($order) {
    $order = array(
        'product' => 0,
        'post' => 1,
        'page' => 2,
        // Add other post types as needed
    );
    return $order;
});

add_filter('relevanssi_indexing_restriction', 'exclude_expired_products_from_indexing');
function exclude_expired_products_from_indexing($restriction)
{
    global $wpdb;
    $current_time = current_time('timestamp');

    // Add a restriction to exclude products where 'start_date_unix_timestamp' is less than the current time
    $restriction['mysql'] .= $wpdb->prepare(
        " AND post.ID NOT IN (
            SELECT post_id FROM {$wpdb->postmeta}
            WHERE meta_key = %s AND meta_value < %d
        )",
        'start_date_unix_timestamp',
        $current_time
    );
    $restriction['reason'] .= ' Excluding expired products.';

    return $restriction;
}
