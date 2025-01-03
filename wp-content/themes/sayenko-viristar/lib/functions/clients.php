<?php

// Add a new column to the 'client' CPT dashboard
/* add_filter('manage_client_posts_columns', 'add_featured_image_column');
function add_featured_image_column($columns)
{
$columns['featured_image_name'] = __('Featured Image Filename', 'textdomain');
return $columns;
}

// Populate the new column with the featured image filename
add_action('manage_client_posts_custom_column', 'populate_featured_image_column', 10, 2);
function populate_featured_image_column($column_name, $post_id)
{
if ($column_name === 'featured_image_name') {
$thumbnail_id = get_post_thumbnail_id($post_id); // Get the ID of the featured image
if ($thumbnail_id) {
$image_url = wp_get_attachment_url($thumbnail_id); // Get the full URL of the image
$image_filename = basename($image_url); // Extract the filename
// echo esc_html($image_filename); // Output the filename
} else {
$image_name = get_field('image_name', $post_id);
echo esc_html($image_name);
}
}
}

// Make the new column sortable (optional)
add_filter('manage_edit-client_sortable_columns', 'make_featured_image_column_sortable');
function make_featured_image_column_sortable($columns)
{
$columns['featured_image_name'] = 'featured_image_name';
return $columns;
}
 */
// Add sticky header body class
add_filter('body_class', function ($classes) {
    if (is_post_type_archive('client') || is_tax('client_category')) {
        $classes[] = 'sticky-header';
    }

    return $classes;
});

add_action('generate_before_main_content', function () {

    if (!is_post_type_archive('client') && !is_tax('client_category')) {
        return;
    }

    add_filter('generate_show_post_navigation', '__return_false');

    add_filter('generate_featured_image_output', function ($output) {
        return sprintf( // WPCS: XSS ok.
            '<div class="post-image">
					%2$s
			 </div>',
            esc_url(get_permalink()),
            get_the_post_thumbnail(
                get_the_ID(),
                apply_filters('generate_page_header_default_size', 'full'),
                array(
                    'itemprop' => 'image',
                )
            )
        );
    });

});

add_action('generate_after_loop', function () {

    if (!is_post_type_archive('client') && !is_tax('client_category')) {
        return;
    }

    echo facetwp_display('facet', 'load_more');
});

add_filter('get_the_archive_title_prefix', '__return_empty_string');

add_action('generate_before_main_content', function () {
    if (!is_post_type_archive('client') && !is_tax('client_category')) {
        return;
    }

    add_action('generate_before_loop', function () {
        ?><div class="facetwp-template"><?php
});

    add_action('generate_after_loop', function () {
        ?></div><?php
}, 1);

    add_action('generate_before_content', function () {
        echo '<div class="post-content">';
    }, 12);

    add_filter('generate_svg_icon', function ($output, $icon) {
        if ('categories' === $icon) {
            return false;
        }

        return $output;
    }, 15, 2);

    add_filter('generate_term_separator', function () {
        return '';
    });

    add_action('generate_after_content', function () {
        echo '</div>';
    });

});

function modify_client_category_posts_per_page($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (is_tax('client_category')) {
        $query->set('posts_per_page', -1); // Change 12 to your desired number
    }
}
add_action('pre_get_posts', 'modify_client_category_posts_per_page');