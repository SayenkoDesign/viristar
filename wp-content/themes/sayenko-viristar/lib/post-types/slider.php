<?php
// Team

add_action('init', function () {


	register_post_type('slider', array(
		'name' => 'slider',
		'label' => 'Slider',
		'active' => true,
		'description' => '',
		'hierarchical' => false,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
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
		'show_in_nav_menus' => false,
		'has_archive' => false,
		'rewrite' => false,
		'capability_type' => 'post',
		'capabilities' => array(),
		'map_meta_cap' => null,
		'show_in_rest' => false
	));

});
