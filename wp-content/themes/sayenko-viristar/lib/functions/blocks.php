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