<?php

namespace App;

add_filter("acf_block_settings", function($defaults) {

	$args = [
		'slug' => 'sayenko',
		'title' => 'Sayenko Blocks'
	];
	
	return wp_parse_args( $args, $defaults );

});

/**
 * Block categories
 */
add_filter( 'block_categories_all', function( $categories ) {

    $theme = wp_get_theme();
	$slug = str_replace('-', '', sanitize_title( $theme->name ) );

	$defaults = [
		'slug' => str_replace('-', '', sanitize_title( wp_get_theme()->name ) ),
		'title' =>  __( $theme->name, $theme->get( 'TextDomain' ) ),
		'icon' => ''
	];

	$settings = apply_filters('acf_block_settings', $defaults );

	// Check to see if we already have a Theme category
	$include = true;
	foreach( $categories as $category ) {
		if( $slug === $category['slug'] ) {
			$include = false;
		}
	}

	if( $include ) {
		$categories = array_merge(
			[
				$settings
			],
			$categories
		);
	}

	return $categories;
} );


/**
 * Create blocks based on templates found in theme "blocks" directory
 */

add_action( 'init', function() {
	$block_directories = glob(get_stylesheet_directory() . "/blocks/*", GLOB_ONLYDIR);

	foreach ($block_directories as $block) {

		$path_parts = pathinfo($block);
		$name = $path_parts['basename'];
		
		$style = get_stylesheet_directory_uri() . sprintf( '/blocks/%s/style.css', $name );
		wp_register_style( $name, $style );

		register_block_type( $block );
	}
});


add_action( 'wp_enqueue_scripts', function() {
	
	if( ! is_singular() ) {
		return;
	}
	
	$block_directories = glob(get_stylesheet_directory() . "/blocks/*", GLOB_ONLYDIR);

	foreach ($block_directories as $block) {

		$path_parts = pathinfo($block);
		$name = $path_parts['basename'];
		
		if( ! has_block( sprintf( 'acf/%s', $name  ) ) ){
			wp_dequeue_style( $name );
		}
		
    };
    
    
}, 10 );