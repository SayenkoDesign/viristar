<?php
// Post

add_action('generate_before_main_content', function() {
	if(!is_singular('case_study')) {
		return;
	}

	add_filter( 'post_thumbnail_html', 'db_featured_image_before_after_content', 10,5 );
 
	function db_featured_image_before_after_content($html, $post_id, $post_thumbnail_id, $size, $attr) {
		
		// If not single or no featured image return
		if( ! is_singular() || $html == '' ) { 
			
			return $html;
		
		} else {
			
			$before_img_html = '<svg fill="none"  viewBox="0 0 386 38" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m386 0h-386v13c0-5.52283 4.47754-10 10-10h178.929c2.202 0 4.343.72729 6.091 2.06885l25.653 19.70105c1.747 1.3417 3.889 2.0688 6.091 2.0688h95.802l53.603.9081c5.456.0924 9.831 4.5418 9.831 9.9986z" fill="#fff" fill-rule="evenodd"/></svg>';
		
			return $before_img_html . $html;
		}

	}

	add_action('generate_after_entry_content', function() {

		generate_content_nav( 'nav-below' );

		get_template_part('template-parts/post', 'share');
	});

});

