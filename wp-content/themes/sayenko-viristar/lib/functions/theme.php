<?php
/*
Header customization
*/
add_filter( 'body_class', function ( $classes ) {
	// Sticky Header
	$sticky_header = get_field('sticky_header');
	if(! empty( $sticky_header ) ) {
		$sticky_header = strtolower($sticky_header);

		if( 'yes' == $sticky_header ) {
			$classes[] = 'sticky-header';
		}
	}
	
	return $classes;
}, 99 );
