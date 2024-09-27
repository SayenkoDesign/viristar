<?php
// Case Studies
// Add sticky header body class
add_filter( 'body_class', function( $classes ) {
	if(is_post_type_archive('client') || is_tax('client_category')) {
		$classes[] = 'sticky-header';
	}

	return $classes;
});



/**
 * Redirects the user to the home page if they are viewing a single client post.
 *
 * This function is hooked to the 'template_redirect' action, which is fired before the template is loaded.
 * If the current page is a single client post, it redirects the user to the home page with a 301 status code.
 * The 'exit' function is called to stop further execution of the script after the redirect.
 */
add_action( 'template_redirect', function() {
	if ( is_singular( 'client' ) ) :
		wp_redirect( home_url(), 301 );
		exit;
	endif;
});


function modify_custom_post_type() {
    global $wp_post_types;

    if (post_type_exists('client')) {
        // Change the query_var to false or to any custom string.
        $wp_post_types['client']->query_var = false;
    }
}
// add_action('init', 'modify_custom_post_type', 100); // Ensure it runs after the CPT is registered


add_action( 'generate_before_main_content', function() {

	if(!is_post_type_archive('client') && !is_tax('client_category')) {
		return;
	}

    add_filter('generate_show_post_navigation', '__return_false');


	add_filter( 'generate_featured_image_output', function( $output ) {
		return sprintf( // WPCS: XSS ok.
			'<div class="post-image">
					%2$s
			 </div>',
			 esc_url( get_permalink() ),
			 get_the_post_thumbnail(
				 get_the_ID(),
				 apply_filters( 'generate_page_header_default_size', 'full' ),
				 array(
					 'itemprop' => 'image',
				 )
			 )
		);
	} );

});

add_action( 'generate_after_loop', function() {

	if(!is_post_type_archive('client') && !is_tax('client_category')) {
		return;
	}

	echo facetwp_display('facet', 'load_more');    
});



add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

add_action('generate_before_main_content', function() {
	if(!is_post_type_archive('client') && !is_tax('client_category')) {
		return;
	}

	

	if( is_tax('client_category') ) {
		$post_type_labels = get_post_type_labels(get_post_type_object('white_paper'));
		$plural_name = $post_type_labels->name;

		printf('<div class="page-header-category"><h3>%s</h3></div>', $plural_name); 
	}

	
	add_action('generate_before_loop', function(){
		?><div class="facetwp-template"><?php
	});
	
	add_action('generate_after_loop', function(){
	?></div><?php
	}, 1);
	
	add_action( 'generate_before_content', function() {
		echo '<div class="post-content">';
	}, 12 );
	
	
	add_filter( 'generate_svg_icon', function( $output, $icon ) {
	if ( 'categories' === $icon ) {
		return false;
	}
	
	return $output;
	}, 15, 2 );
	
	add_filter( 'generate_term_separator', function() {
	return '';
	} );
	
	
	add_action( 'generate_after_content', function() {
		echo '</div>';
	});
	

});
