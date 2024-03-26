<?php



add_action( 'wp', function() {

    // GeneratePress Breakpoints
    add_filter( 'generate_media_queries', function( $queries ) {
        $queries['tablet'] = '(min-width: 768px) and (max-width: 1024px)';
        $queries['mobile'] = '(max-width:767px)';
    
        return $queries;
    } );

    // GenerateBlocks Breakpoints
    /* add_filter( 'generateblocks_media_query', function( $query ) {
        $query['desktop'] = '(min-width: 1025px)';
        $query['tablet'] = '(max-width: 1024px)';
        $query['tablet_only'] = '(max-width: 1024px) and (min-width: 768px)';
        $query['mobile'] = '(max-width: 767px)';

        return $query;
    } ); */
}, 20 );



add_filter( 'generateblocks_default_block_styles', function( $defaultBlockStyles ) {

    $defaultBlockStyles['button'] = array(
        'backgroundColor' => '',
        'textColor' => '',
        'backgroundColorHover' => '',
        'textColorHover' => '',
        'paddingTop' => '',
        'paddingRight' => '',
        'paddingBottom' => '',
        'paddingLeft' => '',
    );

    return $defaultBlockStyles;
}, 99);


add_filter( 'generateblocks_defaults', function( $defaults ) {
    $color_settings = wp_parse_args(
        get_option( 'generate_settings', array() ),
        generate_get_color_defaults()
    );


    $defaults['button']['fontSize'] = 'var(--btn-font-size)';
    $defaults['button']['fontSizeUnit'] = '';

    $defaults['button']['letterSpacing'] = '';

    $defaults['button']['lineHeight'] = 'var(--btn-line-height)';
    $defaults['button']['lineHeightUnit'] = '';
    
    return $defaults;
} );