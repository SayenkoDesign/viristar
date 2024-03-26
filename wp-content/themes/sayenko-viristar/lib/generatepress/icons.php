<?php
// Icons

add_filter('generate_svg_icon', function ($output, $icon) {
    
	if ('menu-bars' === $icon || 'pro-menu-bars' === $icon) {
		$output = '<svg width="39" height="22" viewBox="0 0 39 22" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M1 0.5H31" stroke="currentColor" stroke-linecap="round"/>
        <path d="M1 21.5H31" stroke="currentColor" stroke-linecap="round"/>
        <path d="M1 10.5H38" stroke="currentColor" stroke-linecap="round"/>
        </svg>
        ';

		$classes = array(
			'gp-icon',
			'icon-' . $icon,
		);

		return sprintf(
			'<span class="%1$s">%2$s</span>',
			implode(' ', $classes),
			$output
		);
	}

	return $output;
}, 15, 2);


/* add_filter( 'generate_svg_icon_element', function( $output, $icon ) {
    if ( 'arrow' === $icon ) {
        $output = '<svg fill="none" height="14" viewBox="0 0 9 14" width="9" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m.942414 12.9727c.157446.1488.373106.2273.580276.2273.20716 0 .42241-.0785.57975-.2273l6.1572-5.82297c.15734-.14881.24031-.34475.24031-.54871 0-.20358-.08297-.39951-.24031-.54882l-6.1572-5.822883c-.32328-.305756-.83674-.305756-1.160026 0-.323284.305756-.323284.791383 0 1.097133l5.576926 5.27457-5.576926 5.27458c-.31479.3057-.31479.7994 0 1.0971z" fill="currentColor" fill-rule="evenodd"/></svg>';
    }

    return $output;
}, 15, 2 );

add_filter( 'generate_svg_icon', function( $output, $icon ) {
    $classes = array(
        'gp-icon',
        'icon-' . $icon,
    );
    
    if ( 'pro-close' === $icon ) {
        $output = '<svg viewBox="0 0 85 85" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m66.77 66.77c-.98.98-2.56.98-3.54 0l-20.73-20.73-20.73 20.73c-.98.98-2.56.98-3.54 0s-.98-2.56 0-3.54l20.74-20.73-20.74-20.73c-.98-.98-.98-2.56 0-3.54s2.56-.98 3.54 0l20.73 20.74 20.73-20.73c.98-.98 2.56-.98 3.54 0s.98 2.56 0 3.54l-20.74 20.73 20.73 20.73c.98.97.98 2.56 0 3.53z" fill="#efead6"/></svg>';
    }


    $output = sprintf(
        '<span class="%1$s">%2$s</span>',
        implode( ' ', $classes ),
        $output
    );

    return $output;
}, 15, 2 ); */