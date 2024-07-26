<?php
// Testimonials

add_action('init', function () {


	register_post_type('testimonial', array(
		'name' => 'testimonial',
		'label' => 'Testimonials',
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
		'can_export' => false,
		'delete_with_user' => null,
		'labels' => array(),
		'menu_position' => '',
		'menu_icon' => 'dashicons-admin-post',
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'has_archive' => true,
		'rewrite' => array(
			'slug' => 'testimonials',
			'with_front' => true,
			'feeds' => true,
			'pages' => true,
		),
		'capability_type' => 'post',
		'capabilities' => array(),
		'map_meta_cap' => null,
		'show_in_rest' => false
	));


	$args = [
		'label'  => esc_html__( 'Testimonial Categories', 'viristar' ),
		'labels' => [
			'menu_name'                  => esc_html__( 'Testimonial Categories', 'viristar' ),
			'all_items'                  => esc_html__( 'All Testimonial Categories', 'viristar' ),
			'edit_item'                  => esc_html__( 'Edit Testimonial Category', 'viristar' ),
			'view_item'                  => esc_html__( 'View Testimonial Category', 'viristar' ),
			'update_item'                => esc_html__( 'Update Testimonial Category', 'viristar' ),
			'add_new_item'               => esc_html__( 'Add new Testimonial Category', 'viristar' ),
			'new_item'                   => esc_html__( 'New Testimonial Category', 'viristar' ),
			'parent_item'                => esc_html__( 'Parent Testimonial Category', 'viristar' ),
			'parent_item_colon'          => esc_html__( 'Parent Testimonial Category', 'viristar' ),
			'search_items'               => esc_html__( 'Search Testimonial Categories', 'viristar' ),
			'popular_items'              => esc_html__( 'Popular Testimonial Categories', 'viristar' ),
			'separate_items_with_commas' => esc_html__( 'Separate Testimonial Categories with commas', 'viristar' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Testimonial Categories', 'viristar' ),
			'choose_from_most_used'      => esc_html__( 'Choose most used Testimonial Categories', 'viristar' ),
			'not_found'                  => esc_html__( 'No Testimonial Categories found', 'viristar' ),
			'name'                       => esc_html__( 'Testimonial Categories', 'viristar' ),
			'singular_name'              => esc_html__( 'Testimonial Category', 'viristar' ),
		],
		'public'               => false,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'show_in_nav_menus'    => false,
		'show_tagcloud'        => false,
		'show_in_quick_edit'   => true,
		'show_admin_column'    => false,
		'show_in_rest'         => true,
		'hierarchical'         => true,
		'query_var'            => true,
		'sort'                 => false,
		'rewrite_no_front'     => false,
		'rewrite_hierarchical' => false,
		'rewrite' => false
	];
	register_taxonomy( 'testimonial_category', [ 'testimonial' ], $args );
});
