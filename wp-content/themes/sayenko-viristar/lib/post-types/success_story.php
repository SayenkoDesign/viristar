<?php
// Success Story

add_action('init', function () {

	$args = [
			'label' => 'Success Stories',
			'active' => true,
			'description' => '',
			'hierarchical' => false,
			'supports' => array(
				'title',
				'editor',
				//'thumbnail',
			),
			'taxonomies' => array(),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'can_export' => true,
			'delete_with_user' => null,
			'labels' => array(),
			'menu_position' => '',
			'menu_icon' => 'dashicons-admin-post',
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => true,
			'has_archive' => 'success-stories',
			'rewrite' => true,
			'capability_type' => 'post',
			'capabilities' => array(),
			'map_meta_cap' => null,
			'show_in_rest' => false,
			//'query_var' => false // disables item single view and redirects it to 404 page
	];

	register_post_type('success_story', $args );

});


function get_post_pagination_page_number($post_id) {
    // Get the current post object
    $current_post = get_post($post_id);

    // Check if the current post exists
    if ($current_post) {
        // Get the post type of the current post
        $post_type = $current_post->post_type;

        // Query posts of the same post type
        $args = array(
            'post_type'      => 'success_story', // Specify the custom post type
            'posts_per_page' => -1, // Query all posts
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $query = new WP_Query($args);

        // Get the IDs of all queried posts
        $post_ids = wp_list_pluck($query->posts, 'ID');

        // Determine the position of the current post within the query results
        $current_post_index = array_search($post_id, $post_ids);

        // Calculate the pagination page number
        if ($current_post_index !== false) {
            $posts_per_page = get_option('posts_per_page');
            $pagination_page_number = ceil(($current_post_index + 1) / $posts_per_page);
            return $pagination_page_number;
        }
    }

    // Return null if the post is not found or an error occurs
    return null;
}

// Usage example:
/* $post_id = 102; // Get the ID of the current post
$page_number = get_post_pagination_page_number($post_id); */
