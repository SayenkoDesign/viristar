<?php
// Testimonials


add_action('wp', function() {
	
	if(!is_post_type_archive('testimonial') ){
		return;
	}

	// Add sticky header body class
	add_filter( 'body_class', function( $classes ) {
		$classes[] = 'sticky-header';
		return $classes;
	});
	
	add_action('generate_before_loop', function(){
		?><div class="facetwp-template"><?php
	});
	
	add_action('generate_after_loop', function(){
	?></div><?php
	}, 1);

	add_filter('generate_show_post_navigation', '__return_false');

	add_action( 'generate_after_loop', function() {	
		echo facetwp_display('facet', 'load_more');    
	});
	
});

