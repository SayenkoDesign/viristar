<?php
// Redirects

add_action( 'template_redirect', function() {
	if ( is_singular( 'people' ) ) {
		global $post;
		if( empty( $post->post_content )) {
			wp_redirect( site_url(), 301 );
			exit;
		}
		
	  }
} );