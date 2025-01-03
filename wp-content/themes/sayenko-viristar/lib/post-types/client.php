<?php
// Clients

add_action('init', function () {

    // Register custom post type 'client'
    register_post_type('client', array(
        'name' => 'client',
        'label' => 'Clients',
        'active' => true,
        'description' => '',
        'hierarchical' => false,
        'supports' => array(
            'title',
            'thumbnail',
        ),
        'taxonomies' => array(),
        'public' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'can_export' => true,
        'delete_with_user' => null,
        'labels' => array(),
        'menu_position' => '',
        'menu_icon' => 'dashicons-businessman',
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'has_archive' => 'clients',
        'rewrite' => array(
            'slug' => 'client',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'capabilities' => array(),
        'map_meta_cap' => null,
        'show_in_rest' => false,
    ));

    // Register taxonomy 'client_category' for 'client' post type
    register_taxonomy('client_category', array('client'), array(
        'name' => 'client_category',
        'label' => 'Client Categories',
        'active' => true,
        'post_types' => array('client'),
        'description' => '',
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'update_count_callback' => '',
        'meta_box_cb' => null,
        'sort' => false,
        'labels' => array(),
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'client-categories',
            'with_front' => false,
        ),
    ));
});

// Filter pre get posts

add_action('pre_get_posts', function ($query) {
    // Check if it's the main query on the client CPT archive OR taxonomy, and not an admin page
    if (!is_admin() && ($query->is_main_query() && ($query->is_post_type_archive('client') || $query->is_tax('client_category')))) {
        $query->set('posts_per_page', 60);

        $seed = floor(time() / 900);
        mt_srand($seed);
        $order = (mt_rand(0, 1) === 1) ? 'ASC' : 'DESC';
        $query->set('order', $order);
    }
});
