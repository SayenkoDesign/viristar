<?php

// Blocks

add_action( 'wp_enqueue_scripts', function() {
	
	wp_register_script( 'splide', get_url( 'splide/splide.min.js' ), ['jquery'], '', false );
    wp_register_style( 'splide', get_url( 'splide/splide-core.min.css' ) );
}, 10 );


add_filter( 'acf/blocks/wrap_frontend_innerblocks', 'acf_should_wrap_innerblocks', 10, 2 );
function acf_should_wrap_innerblocks( $wrap, $name ) {
    if ( in_array( $name,  ['acf/hero'] ) ) {
        return false;
    }
    return true;
}

/**
 * Register our custom block style
 *
 * @return void
 */
function wp_dev_register_block_style() {

	register_block_style(
		'core/list',
		array(
			'name'  => 'columns-2', // .is-style-check
			'label' => 'Two Columns',
		)
	);
}
add_action( 'init', 'wp_dev_register_block_style' );