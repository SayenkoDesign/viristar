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


/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/{group}/ directory.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods. For icons with
 * custom (non-square) sizes, set 'size' => false.
 *
 * Icons will be loaded once in the footer and referenced throughout document.
 *
 * @param array $atts Shortcode Attributes.
 */
function be_icon( $atts = array() ) {

	$atts = shortcode_atts(
		[
			'icon'  => false,
			'group' => 'utility',
			'size'  => 24,
			'width' => false,
			'height' => false,
			'class' => false,
			'label' => false,
			'defs'  => false,
			'force' => false,
		],
		$atts
	);

	if ( empty( $atts['icon'] ) ) {
		return;
	}

	if ( is_admin() ) {
		$atts['force'] = true;
	}

	$icon_path = get_theme_file_path( '/assets/icons/' . $atts['group'] . '/' . $atts['icon'] . '.svg' );
	if ( 'images' === $atts['group'] ) {
		$icon_path    = get_theme_file_path( '/assets/images/' . $atts['icon'] . '.svg' );
		$atts['size'] = false;
	}
	if ( ! file_exists( $icon_path ) ) {
		return;
	}

	// Display the icon directly.
	if ( true === $atts['force'] ) {
		ob_start();
		readfile( $icon_path );
		$icon = ob_get_clean();
		if ( false !== $atts['size'] ) {
			$repl = sprintf( '<svg width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['size'], $atts['size'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} elseif( false === $atts['size'] && ! empty( $atts['width'] ) && ! empty( $atts['height'] ) ) {
			$repl = sprintf( '<svg width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['width'], $atts['height'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} else {
			$svg = preg_replace( '/^<svg /', '<svg ', trim( $icon ) );
		}
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.
		if ( ! empty( $atts['class'] ) ) {
			$svg = preg_replace( '/^<svg /', '<svg class="' . $atts['class'] . '"', $svg );
		}

		// Display the icon as symbol in defs.
	} elseif ( true === $atts['defs'] ) {
		ob_start();
		readfile( $icon_path );
		$icon = ob_get_clean();
		$svg  = preg_replace( '/^<svg /', '<svg id="' . $atts['group'] . '-' . $atts['icon'] . '"', trim( $icon ) );
		$svg  = str_replace( '<svg', '<symbol', $svg );
		$svg  = str_replace( '</svg>', '</symbol>', $svg );
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

		// Display reference to icon.
	} else {

		global $be_icons;
		if ( empty( $be_icons[ $atts['group'] ] ) ) {
			$be_icons[ $atts['group'] ] = [];
		}
		if ( empty( $be_icons[ $atts['group'] ][ $atts['icon'] ] ) ) {
			$be_icons[ $atts['group'] ][ $atts['icon'] ] = 1;
		} else {
			$be_icons[ $atts['group'] ][ $atts['icon'] ]++;
		}

		$attr = '';
		if ( ! empty( $atts['class'] ) ) {
			$attr .= ' class="' . esc_attr( $atts['class'] ) . '"';
		}
		if ( false !== $atts['size'] ) {
			$attr .= sprintf( ' width="%d" height="%d"', $atts['size'], $atts['size'] );
		} elseif( false === $atts['size'] && ! empty( $atts['width'] ) && ! empty( $atts['height'] ) ) {
			$attr .= sprintf( ' width="%d" height="%d"', $atts['width'], $atts['height'] );
		}
		if ( ! empty( $atts['label'] ) ) {
			$attr .= ' aria-label="' . esc_attr( $atts['label'] ) . '"';
		} else {
			$attr .= ' aria-hidden="true" role="img" focusable="false"';
		}
		$svg = '<svg' . $attr . '><use href="#' . $atts['group'] . '-' . $atts['icon'] . '"></use></svg>';
	}

	return $svg;
}