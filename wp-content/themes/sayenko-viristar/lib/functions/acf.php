<?php
add_action('acf/init', function () {
    if (!defined('GOOGLE_API_KEY')) {
        return;
    }
    acf_update_setting('google_api_key', GOOGLE_API_KEY);
});

function enqueue_acf_custom_admin_css()
{
    wp_enqueue_style('acf-admin', get_theme_file_uri('dist/styles/acf.css'), [], '1.0');
}
add_action('acf/input/admin_enqueue_scripts', 'enqueue_acf_custom_admin_css');

// Hook into ACF validation
add_filter('acf/validate_value/name=location', 'validate_location_mode', 10, 4);

function validate_location_mode($valid, $value, $field, $input)
{
    // Get the value of the "Mode" field (assuming field name is "mode")
    $mode = isset($_POST['acf']['field_6229fd5d670ba']) ? $_POST['acf']['field_6229fd5d670ba'] : '';

    // Check if the mode is "Classroom" and location is empty
    if ($mode == 'Classroom' && empty($value)) {
        $valid = 'The Location field is required when Mode is set to Classroom';
    }

    return $valid;
}

// Hook into the ACF save post action
add_action('acf/save_post', 'copy_location_data_to_product', 20);

function copy_location_data_to_product($post_id)
{
    // Check if the post type is 'product'
    if (get_post_type($post_id) !== 'product') {
        return;
    }

    // Get the location ID from the product's ACF field (replace 'location' with your actual field key)
    $location_id = get_field('location', $post_id);

    if ($location_id) {
        // Get the latitude and longitude from the 'location' post's 'map' field (replace 'map' with the actual field key)
        $location_map = get_field('map', $location_id);

        if ($location_map && isset($location_map['lat'], $location_map['lng'])) {
            // Update the latitude and longitude in the product (replace 'product_lat' and 'product_lng' with your actual field keys)
            update_field('product_lat', $location_map['lat'], $post_id);
            update_field('product_lng', $location_map['lng'], $post_id);
        }
    }
}

// Hook into ACF save post action when a 'location' post is updated
add_action('acf/save_post', 'update_products_on_location_change_and_reindex_facetwp', 20);

function update_products_on_location_change_and_reindex_facetwp($post_id)
{
    // Check if the post type is 'location'
    if (get_post_type($post_id) !== 'location') {
        return;
    }

    // Get the latitude and longitude from the location's 'map' field
    $location_map = get_field('map', $post_id);

    if ($location_map && isset($location_map['lat'], $location_map['lng'])) {
        // Find all products associated with this location
        $products = new WP_Query(array(
            'post_type' => 'product',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'meta_query' => array(
                array(
                    'key' => 'location', // Field key where the location ID is stored
                    'value' => $post_id,
                    'compare' => '=',
                ),
            ),
        ));

        // Loop through all products and update their latitude and longitude
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();

                $product_id = get_the_ID();

                // Update latitude and longitude for the product
                update_field('product_lat', $location_map['lat'], $product_id);
                update_field('product_lng', $location_map['lng'], $product_id);
            }
            wp_reset_postdata();
        }
    }

    // Reindex FacetWP after saving a location
    if (function_exists('FWP')) {
        FWP()->indexer->index();
    }
}

//add_filter('acf/load_field/name=timezone', 'populate_timezone_field_choices');

function populate_timezone_field_choices($field)
{
    // Clear any existing choices.
    $field['choices'] = array();

    // Get all timezones.
    $timezones = timezone_identifiers_list();

    // Current time to use for calculating the offset.
    $now = new DateTime('now', new DateTimeZone('UTC'));

    // Temporary array to store the formatted display text with their respective timezone identifiers.
    $temp_choices = ['', 'Select Timezone'];

    // Loop through each timezone and add it to the choices array.
    foreach ($timezones as $timezone) {
        $timezone_object = new DateTimeZone($timezone);
        $offset = $timezone_object->getOffset($now);
        $offset_hours = $offset / 3600; // Convert seconds to hours
        $offset_string = ($offset_hours >= 0 ? '+' : '-') . sprintf('%02d:00', abs($offset_hours));

        // Extract city name from the timezone identifier
        $timezone_parts = explode('/', $timezone);
        $city = end($timezone_parts);
        $city = str_replace('_', ' ', $city);

        // Format the display text as "City (UTC±Offset)"
        $display_text = "{$city} (UTC{$offset_string})";

        // Store the display text with its timezone identifier in the temporary array
        $temp_choices[$timezone] = $display_text;
    }

    // Sort the choices by the display text
    asort($temp_choices);

    // Assign the sorted choices to the field
    $field['choices'] = $temp_choices;

    return $field;
}

function _s_get_acf_button($args = [])
{

    if (empty($args)) {
        return false;
    }

    $defaults = array(
        'title' => '',
        'url' => '',
        'target' => '',
        'classes' => '',
        'echo' => false,
    );

    /**
     * Parse incoming $args into an array and merge it with $defaults
     */
    $args = wp_parse_args($args, $defaults);

    extract($args);

    $target = !empty($target) ? sprintf(' target="%s', $target) : '';

    // No link title, bail here!
    if (empty($title) && empty($url)) {
        return false;
    }

    $link = sprintf(' href="%s"', esc_url($url));

    $parts = wp_parse_url($url);

    if (is_array($parts) && !empty($parts['path'])) {

        $path = $parts['path'];

        if ($_post = get_page_by_path(basename(untrailingslashit($path)), OBJECT, 'modal')) {
            $post_id = $_post->ID;
            $link = sprintf('  data-bs-toggle="modal" data-bs-target="#modal-%s" href="javascript:;"', $post_id);
        }
    }

    // Classes
    if (!empty($classes)) {
        if (is_array($classes)) {
            $classes = implode(' ', $classes);
        }

        $classes = sprintf(' class="%s"', esc_attr($classes));
    }

    $output = sprintf(
        '<a%s%s%s><span>%s</span></a>',
        $link,
        $classes,
        esc_attr($target),
        esc_html($title)
    );

    if ($echo) {
        echo $output;
    }

    return $output;
}

// Block: State - Map Filter (icons)
function acf_load_course_icons($field)
{

    // reset choices
    $field['choices'] = array();

    $choices = [];

    $icons = glob(get_stylesheet_directory() . '/assets/icons/courses/*.svg');

    if (empty($icons)) {
        return $field;
    }

    // auto register all blocks that were found.
    foreach ($icons as $icon) {
        $parts = pathinfo($icon);

        $filename = $parts['filename'];

        $name = ucwords(str_replace('-', ' ', $filename));

        $icon = be_icon([
            'icon' => $filename,
            'group' => 'courses',
            'size' => false,
            'force' => true,
        ]);

        $icon = sprintf('<span class="icon" style="display: inline-flex; width: 24px;">%s</span>', $icon);

        $choices[$filename] = $icon;
    }

    // remove any unwanted white space
    $choices = array_map('trim', $choices);

    // loop through array and add to field 'choices'
    if (is_array($choices)) {

        foreach ($choices as $key => $choice) {

            $field['choices'][$key] = $choice;

        }

    }

    // return the field
    return $field;

}

add_filter('acf/load_field/name=course_icon', 'acf_load_course_icons');

add_filter('acf/fields/relationship/result/key=field_65f8d62d1415b', 'append_testimonial_category_to_title', 10, 4);
function append_testimonial_category_to_title($text, $post, $field, $post_id)
{
    // Get the terms of the "testimonial_category" taxonomy
    $terms = get_the_terms($post->ID, 'testimonial_category');

    if (!empty($terms) && !is_wp_error($terms)) {
        $term_names = wp_list_pluck($terms, 'name'); // Extract term names
        $text .= ' (' . implode(', ', $term_names) . ')'; // Append to the title
    }

    return $text;
}
