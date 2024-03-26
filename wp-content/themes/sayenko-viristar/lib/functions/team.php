<?php
// Team

add_filter( 'option_generate_blog_settings', function( $settings ) {
    if ( is_post_type_archive( 'team' ) ) {
        $settings['columns'] = '33';
        $settings['featured_column'] = false;
    }

    return $settings;
} );


add_filter( 'generate_blog_columns',function( $columns ) {
    if ( is_post_type_archive( 'team' ) ) {
        return true;
    }

    return $columns;
}, 15, 1 );