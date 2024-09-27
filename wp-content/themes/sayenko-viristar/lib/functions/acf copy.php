<?php
/* add_action( 'acf/init', function() {
	acf_update_setting( 'google_api_key', GOOGLE_API_KEY );
});
 */



 add_filter('acf/load_field/name=timezone', 'populate_timezone_field_choices');

 function populate_timezone_field_choices($field) {
     // Clear any existing choices.
     $field['choices'] = array();
 
     // Get all timezones.
     $timezones = timezone_identifiers_list();
 
     // Loop through each timezone and add it to the choices array.
     foreach ($timezones as $timezone) {
         $field['choices'][$timezone] = $timezone;
     }
 
     return $field;
 }
 

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header',
		'menu_title' 	=> 'Header',
        'menu_slug' 	=> 'theme-settings-header',
        'parent' 		=> 'theme-settings',
		'capability' => 'edit_posts',
 		'redirect' 	=> false,
        'autoload' => false,
	));  
}


function _s_acf_button( $args = [] ) {
    
    if( empty( $args ) ) {
        return false;
    }
    
    $defaults = array(
        'link' => false,
        'classes' => '',
        'echo' => false
    );
    
    /**
     * Parse incoming $args into an array and merge it with $defaults
     */ 
    $args = wp_parse_args( $args, $defaults );
    
    
        
    extract( $args );
        
	$title   = $link['title'] ?? '';
    $url     = $link['url'] ?? '';
    $target  = ! empty( $link['target'] ) ? sprintf(' target="%s', $link['target'] ) : '';
    
    // No link title, bail here!
	if ( empty( $title ) && empty( $url ) ) {
		return false;
	}
    
    $link = sprintf( ' href="%s"', esc_url( $url ) ); 
               
    $parts = wp_parse_url( $url );
    
    if( is_array( $parts ) && ! empty( $parts['path'] ) ) {
        
        $path = $parts['path'];

        if ( $_post = get_page_by_path( basename( untrailingslashit( $path ) ), OBJECT, 'modal' ) ) {
            $post_id = $_post->ID;
            $slug = sanitize_title_with_dashes( get_the_title( $post_id ) );
            if( is_array( $classes ) ) {
                $classes[] = 'modal-form';
            } else {
                  $classes .= ' modal-form'; 
            }
            
            $link = sprintf( ' data-fancybox="modal-%d" data-src="#%s" data-touch="false" data-auto-focus="false" data-thumbs="false" href="javascript:;"', wp_unique_id('-'), $slug );
        }  
    }
    
    // Classes
    if( ! empty( $classes ) ) {
        if( is_array( $classes ) ) {
            $classes = implode( ' ', $classes );
        }
        
        $classes = sprintf( ' class="%s"', esc_attr( $classes ) );
    }

	$output = sprintf(
		'<a%s%s%s><span>%s</span></a>',
		$link,
		$classes,
        esc_attr( $target ),
		esc_html( $title )
	);

	if ( $echo ) {
		echo $output;
	}

	return $output;
}



// Modal links open in fancybox
function _s_menu_item_fancybox($item_output, $item ) {
    if( ! empty( $item->object ) && 'modal' === $item->object ) {
        $slug = sanitize_title_with_dashes( $item->title );
        $post_id = $item->object_id;
                
        return sprintf( '<a data-fancybox="modal-%d" data-src="#%s" data-touch="false" data-auto-focus="false" data-thumbs="false" href="javascript:;">%s</a>', wp_unique_id('-'), $slug, $item->title );
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el','_s_menu_item_fancybox',10,2);


// filter for a specific field based on it's name
function my_relationship_query( $args, $field, $post_id ) {
	
    // exclude current post from being selected
    $args['exclude'] = $post_id;
	
	
	// return
    return $args;
    
}
add_filter('acf/fields/relationship/query/name=posts', 'my_relationship_query', 10, 3);

/**
 * Populate ACF select field options with Gravity Forms forms
 */
function acf_populate_gf_forms_ids( $field ) {
	if ( class_exists( 'GFFormsModel' ) ) {
		$choices = [];

		foreach ( \GFFormsModel::get_forms() as $form ) {
			$choices[ $form->id ] = $form->title;
		}

		$field['choices'] = $choices;
	}

	return $field;
}
add_filter( 'acf/load_field/name=gravity_form', 'acf_populate_gf_forms_ids' );
