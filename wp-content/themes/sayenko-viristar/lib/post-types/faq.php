<?php
// FAQ

add_action('init', function () {

    register_post_type('faq', array(
        'name' => 'faq',
        'label' => 'FAQ',
        'active' => true,
        'description' => '',
        'hierarchical' => false,
        'supports' => array(
            'title',
            'editor',
            'page-attributes',
        ),
        'taxonomies' => array(),
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'can_export' => true,
        'delete_with_user' => null,
        'labels' => array(),
        'menu_position' => null,
        'menu_icon' => 'dashicons-admin-post',
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'capabilities' => array(),
        'map_meta_cap' => null,
        'show_in_rest' => false,
    ));

    register_taxonomy('topic', array(
        'faq',
    ), array(
        'name' => 'topic',
        'label' => 'Topics',
        'active' => true,
        'post_types' => array(
            'faq',
        ),
        'description' => '',
        'hierarchical' => true,
        'public' => false,
        'publicly_queryable' => false,
        'update_count_callback' => '',
        'meta_box_cb' => null,
        'sort' => false,
        'labels' => array(),
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
    ));
});
