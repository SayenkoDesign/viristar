<?php
// Mega Menu

add_action('init', function () {


	register_post_type('mega_menu', array(
		'name' => 'mega_menu',
		'label' => 'Menu Block',
		'active' => true,
		'description' => '',
		'hierarchical' => false,
		'supports' => array(
			'title',
			'editor',
		),
		'taxonomies' => array(),
		'public' => true,
		'exclude_from_search' => true,
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
		'has_archive' => false,
		'rewrite' => true,
		'capability_type' => 'post',
		'capabilities' => array(),
		'map_meta_cap' => null,
		'show_in_rest' => true
	));
});
