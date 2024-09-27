<?php
// Mega Menu

/* function _s_mega_menu_placeholder_image($item_output, $item) {
    
	$mega_menu = get_field('mega_menu', $item );  

	$args = [ '_post' => $mega_menu ];

	if( ! empty( $mega_menu ) ) {
		return _s_get_template_part( 'template-parts', 'mega-menu', $args, true );
	}

    return $item_output;
}
add_filter('walker_nav_menu_start_el','_s_mega_menu_placeholder_image',10,2); */


function _s_mega_menu_placeholder_image($item_output, $item) {
    
	if (isset($item->type) && $item->type == 'post_type') {
        // Get the ID of the linked post
        $post_id = $item->object_id;
        
        // Get the post by ID and then its post type
        $post = get_post($post_id);
        $post_type = get_post_type($post);

        // Check if the post exists
        if ('mega_menu' == $post_type) {
            // Optionally, you might want to apply the_content filter to execute shortcodes and auto-paragraphs
            $post_content = apply_filters('the_content', $post->post_content);

            // Replace the default item output with the post's content
            $item_output = sprintf( '<div class="menu-block-content">%s</div>', $post_content );
        }
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el','_s_mega_menu_placeholder_image',10,2);