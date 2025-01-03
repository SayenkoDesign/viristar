<?php

function format_time_as_words($options)
{
    if (!is_array($options)) {
        return '';
    }

    // Extract values from the options array
    $when = $options['when'] ?? 'Never';
    $when = strtolower($when);

    $time = $options['time'] ?? 0;
    $units = $options['units'] ?? 'days';

    if ('never' == $when) {
        return 'Never';
    }

    if (1 == $time) {
        $units = rtrim($units, 's');
    }

    // Format as human-readable text
    return "$time $units $when ";
}

function calculate_time($options, $date)
{
    // Check if options is an array; if not, return the original date as-is
    if (!is_array($options)) {
        return $date;
    }

    // Extract values from the options array
    $when = strtolower($options['when'] ?? 'after');
    $time = $options['time'] ?? 0;
    $units = $options['units'] ?? 'days';

    if ('never' == $when) {
        return $date;
    }

    // Create a DateTime object from the given date
    $date = new DateTime($date);

    // Define the interval specification based on the units
    switch ($units) {
        case 'hours':
            $intervalSpec = 'PT' . $time . 'H';
            break;
        case 'days':
        default:
            $intervalSpec = 'P' . $time . 'D';
            break;
    }

    // Adjust the date based on "before" or "after"
    if ($when === 'before') {
        $date->sub(new DateInterval($intervalSpec));
    } else {
        $date->add(new DateInterval($intervalSpec));
    }

    // Return the adjusted date in a readable format
    return $date->format('Y-m-d H:i:s');
}

// Add custom columns to the product post type admin list
add_filter('manage_product_posts_columns', 'custom_product_columns', 99);
function custom_product_columns($columns)
{
    // Find the position of the date column
    $date_position = array_search('date', array_keys($columns));

    // Create an array of the custom columns you want to add
    $custom_columns = [
        'start_date' => __('Start Date', 'viristar'),
        'end_date' => __('End Date', 'viristar'),
        'registration_deadline' => __('Registration Deadline', 'viristar'),
        'disable_buy_now' => __('Disable Button', 'viristar'),
    ];

    // Splice the custom columns into the columns array before the date
    $columns = array_slice($columns, 0, $date_position, true)
     + $custom_columns
     + array_slice($columns, $date_position, null, true);

    return $columns;
}

function viristar_course_in_stock($product_id)
{

    $course_type = get_field('course_type', $product_id);

    if (empty($course_type)) {
        return true;
    }

    // Has the date passed
    $start_date_unix_timestamp = get_field('start_date_unix_timestamp', $product_id);
    $current_time = time();

    if ($current_time >= $start_date_unix_timestamp) {
        return false;
    }

    return true;
}

// Populate the custom columns with data
add_action('manage_product_posts_custom_column', 'custom_product_column_content', 99, 2);
function custom_product_column_content($column, $post_id)
{

    switch ($column) {
        case 'start_date':
            $start_date = get_field('start_date', $post_id);
            if (!empty($start_date)) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $start_date);
                echo $date->format('F j, Y g:i A'); // Outputs: January 5, 2004 2:30 PM
            }
            break;
        case 'end_date':
            $end_date = get_field('end_date', $post_id);
            if (!empty($end_date)) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $end_date);
                echo $date->format('F j, Y g:i A'); // Outputs: January 5, 2004 2:30 PM
            }
            break;
        case 'registration_deadline':
            $registration_deadline = get_field('registration_deadline', $post_id);
            $course_type = get_field('course_type', $post_id);
            if (!empty($course_type) && !empty($registration_deadline)) {
                $return = format_time_as_words($registration_deadline);

                if ('Never' === $return) {
                    echo 'Start Date';
                } else {
                    echo $return;
                }
            }
            break;
        case 'disable_buy_now':
            $disable_buy_now = get_field('disable_buy_now', $post_id);

            $course_type = get_field('course_type', $post_id);
            if (!empty($course_type) && !empty($disable_buy_now)) {
                echo format_time_as_words($disable_buy_now);

                $start_date_unix_timestamp = get_field('start_date_unix_timestamp', $post_id);
                $timezone = get_field('timezone', $post_id);

                /* if (!empty($start_date_unix_timestamp)) {
            echo '<br />';
            // Create a DateTime object from the Unix timestamp
            $date = new DateTime();
            $date->setTimestamp($start_date_unix_timestamp);

            // Set the timezone
            $date->setTimezone(new DateTimeZone($timezone));

            // Output the date in the desired format and timezone
            echo $date->format('F j, Y g:i A');
            } */
            }
            break;
        case 'is_in_stock':
            $course_type = get_field('course_type', $post_id);
            if (!empty($course_type)) {
                echo viristar_course_in_stock($post_id) ? '<br />Course Available' : '<br />Registration Deadline has Passed';
            }

            break;
    }
}

add_action('viristar_course_product_content', 'viristar_add_content_before_product_details', 10);

function viristar_add_content_before_product_details()
{
    $product_id = get_the_ID();
    $course_id = get_field('course', $product_id);

    $course_finder_url = get_field('course_finder_url', $course_id);
    $default_filter_url = sprintf('%s?_product_course_finder=%d', get_permalink(612), $course_id);
    $url = !empty($course_finder_url) ? $course_finder_url : $default_filter_url;

    if (false === viristar_course_in_stock($product_id)) {
        printf('<div class="viristar-product-course-message alignfull"><div class="content-width">The Registration Deadline has Passed. Please <a href="%s">click here</a> to find available course dates.</div></div>', $url);
    }
}
