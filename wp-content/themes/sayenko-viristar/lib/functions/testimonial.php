<?php
// Testimonials

// Add a new column for the logo filename
add_filter('manage_testimonial_posts_columns', 'add_testimonial_logo_image_column');
function add_testimonial_logo_image_column($columns)
{
    $columns['logo_name'] = __('Logo Filename', 'textdomain');
    return $columns;
}

// Populate the new column with the logo filename or fallback to 'logo_name'
add_action('manage_testimonial_posts_custom_column', 'populate_testimonial_logo_image_column', 10, 2);
function populate_testimonial_logo_image_column($column_name, $post_id)
{
    if ($column_name === 'logo_name') {
        $logo = get_field('logo', $post_id);
        $logo_name = get_field('logo_name', $post_id);

        if (!empty($logo)) {
            // echo 'has logo';
        } else {
            if (!empty($logo_name)) {
                echo esc_html($logo_name);
            } else {
                echo __('No logo available', 'textdomain');
            }
        }
    }
}

function include_categories_in_testimonial_archive($query)
{
    // Check if it's the main query on the testimonial CPT archive and not an admin page.
    if (!is_admin() && ($query->is_post_type_archive('testimonial') && $query->is_main_query())) {

        $query->set('ignore_custom_sort', true);

        $seed = floor(time() / 900);
        mt_srand($seed);
        $order = (mt_rand(0, 1) === 1) ? 'ASC' : 'DESC';
        $query->set('order', $order);

        $included_categories = [];

        // Get all terms from the 'testimonial_category' taxonomy.
        $testimonial_categories = get_terms([
            'taxonomy' => 'testimonial_category',
            'hide_empty' => false,
        ]);

        // Loop through terms and check the 'archive_include' ACF field.
        if (!is_wp_error($testimonial_categories) && !empty($testimonial_categories)) {
            foreach ($testimonial_categories as $category) {
                $archive_include = get_field('archive_include', 'testimonial_category_' . $category->term_id);
                if ($archive_include) {
                    $included_categories[] = $category->term_id;
                }
            }
        }

        // If no categories are included, return early.
        if (empty($included_categories)) {
            return;
        }

        // Modify the query to include only posts from the selected categories.
        $query->set('tax_query', [
            [
                'taxonomy' => 'testimonial_category',
                'field' => 'term_id',
                'terms' => $included_categories,
                'operator' => 'IN',
            ],
        ]);

        // Add sanitization at the start of the testimonial ID check
        if (isset($_GET['testimonial_id']) && !empty($_GET['testimonial_id'])) {
            $testimonial_id = filter_var($_GET['testimonial_id'], FILTER_VALIDATE_INT);

            // Only exclude if it's a valid positive integer
            if ($testimonial_id > 0) {
                $query->set('post__not_in', [$testimonial_id]);
            }
        }
    }
}
add_action('pre_get_posts', 'include_categories_in_testimonial_archive', 99);

add_action('wp', function () {

    if (!is_post_type_archive('testimonial')) {
        return;
    }

    // Add sticky header body class
    add_filter('body_class', function ($classes) {
        $classes[] = 'sticky-header';
        return $classes;
    });

    add_action('generate_before_loop', function () {
        ?><div class="facetwp-template"><?php
});

    add_action('generate_after_loop', function () {
        ?></div><?php
}, 1);

    add_filter('generate_show_post_navigation', '__return_false');

    add_action('generate_after_loop', function () {
        echo facetwp_display('facet', 'load_more');
    });

});