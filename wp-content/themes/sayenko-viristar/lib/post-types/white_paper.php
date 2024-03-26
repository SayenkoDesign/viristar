<?php
// Case Study

add_action('init', function () {

register_post_type('white_paper', array(
    'name' => 'white_paper',
    'label' => 'White Papers',
    'active' => true,
    'description' => '',
    'hierarchical' => false,
    'supports' => array(
        'title',
        'editor',
        'thumbnail',
    ),
    'taxonomies' => array(),
    'public' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'can_export' => true,
    'delete_with_user' => null,
    'labels' => array(),
    'menu_position' => '',
    'menu_icon' => 'dashicons-admin-post',
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_in_admin_bar' => true,
    'has_archive' => 'white-papers',
    'rewrite' => array(
        'slug' => 'white-paper',
        'with_front' => true,
        'feeds' => true,
        'pages' => true,
    ),
    'capability_type' => 'post',
    'capabilities' => array(),
    'map_meta_cap' => null,
    'show_in_rest' => true
));


register_taxonomy('white_paper_solution', array(
    'white_paper',
), array(
    'name' => 'solution',
    'label' => 'Solutions',
    'active' => true,
    'post_types' => array(
        'white_paper',
    ),
    'rewrite' => array(
        'slug' => 'white-paper-solution',
    ),
    'description' => '',
    'hierarchical' => true,
    'public' => true,
    'publicly_queryable' => true,
    'update_count_callback' => '',
    'meta_box_cb' => null,
    'sort' => false,
    'labels' => array(),
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud' => true,
    'show_in_quick_edit' => true,
    'show_admin_column' => true,
	'show_in_rest' => true
));


});


add_filter( 'generate_footer_meta_post_types', function( $types ) {
    $types[] = 'white_paper';

    return $types;
} );


add_action( 'generate_post_meta_items', function( $item ) {
    if ( 'white_paper_solution' === $item ) {
        $terms = get_the_term_list( get_the_ID(), $item, '<ul class="post-categories"><li>', '</li><li>', '</li></ul>' );

        if ( $terms ) {
            printf(
                '<span class="solution-links">%3$s<span class="screen-reader-text">%1$s </span>%2$s</span> ',
                esc_html_x( 'Terms', 'Used before tag names.', 'generatepress' ),
                $terms,
                apply_filters( 'generate_inside_post_meta_item_output', '', $item )
            );
        }
    }

} );

add_filter( 'generate_footer_entry_meta_items', function( $items ) {
    if ( 'white_paper' === get_post_type() ) {
        return array(
            'white_paper_solution'
        );
    }

    return $items;
} );
