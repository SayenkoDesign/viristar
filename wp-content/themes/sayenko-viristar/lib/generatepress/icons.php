<?php
// Icons

add_filter('generate_svg_icon', function ($output, $icon) {
    
	if ('menu-bars' === $icon || 'pro-menu-bars' === $icon) {
		$output = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="25" viewBox="0 0 32 25" fill="none">
  <path d="M2 12H21.7647" stroke="currentColor" stroke-width="3" stroke-linecap="square"/>
  <path d="M2 22H30" stroke="currentColor" stroke-width="3" stroke-linecap="square"/>
  <path d="M2 2H30" stroke="currentColor" stroke-width="3" stroke-linecap="square"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4416 12.2948L24.087 2.6494C24.6995 2.03687 24.6995 1.11712 24.087 0.503631C23.4735 -0.106028 22.5576 -0.106028 21.9441 0.503631L12.2948 10.1529L2.6494 0.503631C2.03687 -0.106028 1.11712 -0.106028 0.503631 0.503631C-0.106028 1.11712 -0.106028 2.03687 0.503631 2.6494L10.1529 12.2948L0.503631 21.9441C-0.106028 22.5576 -0.106028 23.4735 0.503631 24.087C1.11712 24.6995 2.03687 24.6995 2.6494 24.087L12.2948 14.4416L21.9441 24.087C22.5576 24.6995 23.4735 24.6995 24.087 24.087C24.6995 23.4735 24.6995 22.5576 24.087 21.9441L14.4416 12.2948Z" fill="currentColor"/>
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


add_filter( 'generate_svg_icon', function( $output, $icon ) {
    if ( 'shopping-cart' === $icon ) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="24" viewBox="0 0 26 24" fill="none">
        <path d="M10.0605 23.864C8.64771 23.864 7.49829 22.7146 7.49829 21.3018C7.49829 19.8891 8.64771 18.7401 10.0605 18.7401C11.4732 18.7401 12.6226 19.8891 12.6226 21.3018C12.6226 22.7146 11.4732 23.864 10.0605 23.864ZM10.0605 20.3482C9.5346 20.3482 9.10643 20.776 9.10643 21.3018C9.10643 21.8281 9.5342 22.2558 10.0605 22.2558C10.5867 22.2558 11.0145 21.8281 11.0145 21.3018C11.0145 20.776 10.5867 20.3482 10.0605 20.3482Z" fill="currentColor"/>
        <path d="M18.9672 23.864C17.5544 23.864 16.4054 22.7146 16.4054 21.3018C16.4054 19.8891 17.5544 18.7401 18.9672 18.7401C20.3799 18.7401 21.5293 19.8891 21.5293 21.3018C21.5293 22.7146 20.3795 23.864 18.9672 23.864ZM18.9672 20.3482C18.4413 20.3482 18.0135 20.776 18.0135 21.3018C18.0135 21.8281 18.4413 22.2558 18.9672 22.2558C19.4934 22.2558 19.9212 21.8281 19.9212 21.3018C19.9212 20.776 19.493 20.3482 18.9672 20.3482Z" fill="currentColor"/>
        <path d="M20.8431 16.754H8.21156C6.98254 16.754 5.93283 15.9174 5.65864 14.7193L3.03697 3.27055C3.00239 3.11939 2.89787 2.98993 2.75715 2.9248L0.582547 1.9169C0.0791995 1.68372 -0.13991 1.0863 0.0932707 0.582547C0.326451 0.0791995 0.924277 -0.13991 1.42762 0.0932707L3.60223 1.10117C4.30298 1.42561 4.82442 2.06887 4.99649 2.82188L7.61816 14.2714C7.68168 14.5496 7.92571 14.7442 8.21156 14.7442H20.8431C21.1277 14.7442 21.3714 14.5508 21.4365 14.2734L23.4748 5.50385C23.5331 5.25459 23.427 5.07328 23.359 4.98764C23.2907 4.90161 23.1379 4.75768 22.8822 4.75768H8.00692C7.45171 4.75768 7.00184 4.3078 7.00184 3.75259C7.00184 3.19738 7.45171 2.7475 8.00692 2.7475H22.8822C23.6859 2.7475 24.4329 3.10813 24.933 3.73731C25.4331 4.3665 25.6148 5.1762 25.4331 5.95896L23.3944 14.7289C23.1166 15.921 22.0677 16.754 20.8431 16.754Z" fill="currentColor"/>
      </svg>';

        return sprintf(
            '<span class="gp-icon %1$s">
                %2$s
            </span>',
            $icon,
            $svg
        );
    }

    return $output;
}, 15, 2 );


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