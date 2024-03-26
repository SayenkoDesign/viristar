<?php



/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 */
function _s_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	$located = _s_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		/* translators: %s template */
		_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', '_s' ), '<code>' . $located . '</code>' ), '1.0' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( '_s_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( '_s_before_template', $template_name, $template_path, $located, $args );

	include $located;

	do_action( '_s_after_template', $template_name, $template_path, $located, $args );
}


function _s_get_template_part( $template_path, $template_name, $data = array(), $return = false ) {
        
    $template_name = "{$template_name}.php";
    
    $template = _s_locate_template( $template_name, $template_path );
    
    if( ! $template )
        return;
            
    if( is_array( $data ) ) {
        extract( $data );
    }
    
    // Return instead of echo
    if( $return ) {
        
        ob_start();
        include( $template );
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
        
    include( $template );
    
}



/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @access public
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 * @return string
 */
function _s_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = 'page-templates';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template ) {
		_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', '_s' ), '<code>' . $template_name . '</code>' ), '1.0' );
        return;
	}

	// Return what we found.
	return apply_filters( '_s_locate_template', $template, $template_name, $template_path );
}
