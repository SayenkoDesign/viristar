<?php

/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/{group}/ directory, without adding
 * both `width` and `height` attributes, since these are added dynamically,
 * before rendering the SVG code.
 *
 *
 */
function _s_get_icon( $atts = array() ) {

	$atts = shortcode_atts( array(
		'icon'	=> false,
		'group'	=> 'utility',
        'class' => 'svg-icon',
		'width'	=> 16,
        'height' => 16,
        'role' => 'img',
        'aria-hidden' => 'true',
        'focusable' => 'false'
	), $atts );

	if( empty( $atts['icon'] ) )
		return;

	$icon_path = get_theme_file_path( '/assets/icons/' . $atts['group'] . '/' . $atts['icon'] . '.svg' );

	if( ! file_exists( $icon_path ) )
		return;

		
    
    // remove icon and group 
    unset( $atts['icon'], $atts['group'] );

    $icon = file_get_contents( $icon_path );
    
    $attr = _parse_icon_attribute( $atts );
    
    $svg  = preg_replace( '/^<svg /', '<svg ' . $attr . ' ', trim( $icon ) ); // Add extra attributes to SVG code.
        
    $svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
    $svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

    return $svg;
}


function _parse_icon_attribute( $attr ) {
	if( is_array( $attr ) ) {
		$t = [];
		foreach( $attr as $k => $v ) {
			if( ! empty( $v ) ) {
                $out = sprintf('%s="%s"', $k, esc_attr( $v ) );
                $t[] = $out;
			}
		}
		return implode( ' ', $t );
	}
	else {
		return $attr;	
	}
}


// Icons

function get_accent($name = '') {
	if( empty( $name )) {
		return;
	}

	// aria-hidden="true" focusable="false"

	if( 'blue-orange' == $name ) {
		return '<svg fill="none" height="121" aria-hidden="true" focusable="false" viewBox="0 0 141 121" width="141" xmlns="http://www.w3.org/2000/svg"><circle cx="60.5" cy="60.5" fill="#20aad5" r="60.5"/><circle cx="120.5" cy="68.5" fill="#e98300" r="20.5"/></svg>';
	}

	if( 'green-blue' == $name ) {
		return '<svg fill="none" height="121" aria-hidden="true" focusable="false" viewBox="0 0 121 121" width="121" xmlns="http://www.w3.org/2000/svg"><circle cx="60.5" cy="60.5" fill="#ced800" r="60.5"/><circle cx="81.5" cy="48.5" fill="#20aad5" r="20.5"/></svg>';
	}

	if( 'green-orange' == $name ) {
		return '<svg fill="none" height="121" aria-hidden="true" focusable="false" viewBox="0 0 141 121" width="141" xmlns="http://www.w3.org/2000/svg"><circle cx="60.5" cy="60.5" fill="#ced800" r="60.5"/><circle cx="120.5" cy="68.5" fill="#e98300" r="20.5"/></svg>';
	}

	if( 'orange-blue' == $name ) {
		return '<svg fill="none" height="88" aria-hidden="true" focusable="false" viewBox="0 0 108 88" width="108" xmlns="http://www.w3.org/2000/svg"><circle cx="44" cy="44" fill="#ff8f00" r="44"/><circle cx="87.5" cy="20.5" fill="#20aad5" r="20.5"/></svg>';
	}

	if( 'orange-green-down' == $name ) {
		return '<svg fill="none" height="81" aria-hidden="true" focusable="false" viewBox="0 0 100 81" width="100" xmlns="http://www.w3.org/2000/svg"><circle cx="40" cy="40" fill="#ff8f00" r="40"/><circle cx="79.5" cy="60.5" fill="#ced800" r="20.5"/></svg>';
	}

	if( 'orange-green-up' == $name ) {
		return '<svg fill="none" height="101" aria-hidden="true" focusable="false" viewBox="0 0 86 101" width="86" xmlns="http://www.w3.org/2000/svg"><circle cx="40" cy="61" fill="#ff8f00" r="40"/><circle cx="65.5" cy="20.5" fill="#ced800" r="20.5"/></svg>';
	}
	
	
}