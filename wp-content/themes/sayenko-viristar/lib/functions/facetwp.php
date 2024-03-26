<?php

if (!function_exists('FWP')) {
    return;
}


add_filter( 'facetwp_pager_html', function( $output, $params ) {
    $output = '';
    $page = $params['page'];
    $total_pages = $params['total_pages'];

    if ( 1 < $total_pages ) {

        // Previous page (NEW)
        if ( $page > 1 ) {
            $output .= '<a class="facetwp-page" data-page="' . ($page - 1) . '">Previous</a>';
        }
        
        if ( 3 < $page ) {
            $output .= '<a class="facetwp-page first-page" data-page="1"><<</a>';
        }
        if ( 1 < ( $page - 10 ) ) {
            $output .= '<a class="facetwp-page" data-page="' . ($page - 10) . '">' . ($page - 10) . '</a>';
        }
        for ( $i = 2; $i > 0; $i-- ) {
            if ( 0 < ( $page - $i ) ) {
                $output .= '<a class="facetwp-page" data-page="' . ($page - $i) . '">' . ($page - $i) . '</a>';
            }
        }

        // Current page
        $output .= '<a class="facetwp-page active" data-page="' . $page . '">' . $page . '</a>';

        for ( $i = 1; $i <= 2; $i++ ) {
            if ( $total_pages >= ( $page + $i ) ) {
                $output .= '<a class="facetwp-page" data-page="' . ($page + $i) . '">' . ($page + $i) . '</a>';
            }
        }
        if ( $total_pages > ( $page + 10 ) ) {
            $output .= '<a class="facetwp-page" data-page="' . ($page + 10) . '">' . ($page + 10) . '</a>';
        }
        if ( $total_pages > ( $page + 2 ) ) {
            $output .= '<a class="facetwp-page last-page" data-page="' . $total_pages . '">>></a>';
        }

        // Next page (NEW)
        if ( $page < $total_pages ) {
            $output .= '<a class="facetwp-page" data-page="' . ($page + 1) . '">Next</a>';
        }
    }

    return $output;
}, 10, 2 );

/* add_filter('facetwp_facet_render_args', function ($args) {

    if (is_home() || is_category()) {
        $prev_icon = _s_get_icon(
            [
                'icon'    => 'prev-arrow',
                'group'    => 'theme',
                'class'    => 'previous',
                'width' => 14,
                'height' => 26,
                'label'    => false,
            ]
        );
        $prev = sprintf('<span class="screen-reader-text" title="%1$s">%1$s</span>%2$s', esc_attr__('Previous', 'generatepress'), $prev_icon);

        $next_icon = _s_get_icon(
            [
                'icon'    => 'next-arrow',
                'group'    => 'theme',
                'class'    => 'next',
                'width' => 14,
                'height' => 26,
                'label'    => false,
            ]
        );
        $next = sprintf('<span class="screen-reader-text" title="%1$s">%1$s</span>%2$s', esc_attr__('Next', 'generatepress'), $next_icon);

        $args['facet']['prev_label'] = $prev;
        $args['facet']['next_label'] = $next;
    }


    return $args;
}); */




add_filter( 'facetwp_facet_dropdown_show_counts', function( $return, $params ) {
    return false;
}, 10, 2 );