<?php

if (!function_exists('FWP')) {
    return;
}

add_action('wp_head', function () {
    ?>
    <style>
      .wp-block-acf-find-a-course .courses {
        display: none;
      }
      .wp-block-acf-find-a-course.facetwp-visible .courses {
        display: block;
      }
    </style>
    <?php
}, 100);

add_action('wp_head', function () {
    ?>
    <script>
        (function($) {
            $(document).on('facetwp-loaded', function() {
                var facets_in_use = ('' != FWP.buildQueryString());

                // see https://api.jquery.com/toggle/
                // TRUE to show, FALSE to hide
                $('.reset').toggle(facets_in_use);
            });


        })(jQuery);
    </script>
<?php
}, 100);

// Index the course category instead of post ID
add_filter('facetwp_index_row', function ($params, $class) {
    if ('product_course_category' == $params['facet_name']) {
        $post_id = $params['post_id'];
        $course_id = get_field('course', $post_id); // Get the course ID

        if ($course_id) {
            // Get the single term (category) for this course
            $terms = get_the_terms($course_id, 'course_category'); // Replace 'course_category' with your actual taxonomy name if different

            if ($terms && !is_wp_error($terms) && !empty($terms)) {
                $term = reset($terms); // Get the first (and only) term

                $params['facet_value'] = $term->term_id;
                $params['facet_display_value'] = $term->name;
            }
        }
    }
    return $params;
}, 10, 2);

// Ensure the facet displays the category name
/* add_filter('facetwp_facet_display_value', function($display_value, $params) {
if ('product_course_category' == $params['facet']['name']) {
return $display_value; // At this point, $display_value should already be the category name
}
return $display_value;
}, 10, 2);
 */

// We need the product start date as a month
/* add_filter('facetwp_index_row', function ($params) {
if ('product_course_date_month' == $params['facet_name']) {
$date = $params['facet_value'];
$params['facet_value'] = date('nY', strtotime($date));
$params['facet_display_value'] = date('F Y', strtotime($date));
}
return $params;
}, 10, 2); */

add_filter('facetwp_index_row', function ($params) {
    if ('product_course_date_month' == $params['facet_name']) {
        // Define an array of all months
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        // Loop through all months to create facet rows
        $rows = [];
        foreach ($months as $num => $name) {
            $rows[] = [
                'facet_name' => $params['facet_name'],
                'facet_value' => $num, // Numeric representation of the month
                'facet_display_value' => $name, // Full month name
                'term_id' => 0, // Optional: Set to 0 if not applicable
            ];
        }

        // Return the custom rows for all months
        return $rows;
    }

    return $params;
}, 10, 2);

/* add_filter('facetwp_query_args', function ($args, $class) {
// Identify your facet name that filters by month
$month_facet_name = 'product_course_date_month'; // Change this to your facet name

// Get the selected month value
$selected_month = FWP()->facet->query_vars[$month_facet_name] ?? '';

if (!empty($selected_month)) {
// Parse the selected month (assuming it's in "YYYY-MM" format)
$start_of_month = date('Y-m-01', strtotime($selected_month));
$end_of_month = date('Y-m-t', strtotime($selected_month));

// Modify the query to include posts with start or end dates within the selected month
$args['meta_query'][] = [
'relation' => 'OR',
[
'key' => 'start_date',
'value' => [$start_of_month, $end_of_month],
'compare' => 'BETWEEN',
'type' => 'DATE'
],
[
'key' => 'end_date',
'value' => [$start_of_month, $end_of_month],
'compare' => 'BETWEEN',
'type' => 'DATE'
]
];
}

return $args;
}, 10, 2);
 */

/*
This tricks it show the start and end date.

add_filter( 'facetwp_index_row', function( $params, $class ) {
if ( 'product_course_date_month_end' == $params['facet_name'] ) {
$params['facet_name'] = 'product_course_date_month';
$date = $params['facet_value'];
$params['facet_value'] = date('nY', strtotime($date));
$params['facet_display_value'] = date('F Y', strtotime($date));
}
return $params;
}, 10, 2 );
 */

/**
 * Get ordered course titles as a quoted array
 *
 * @return array Array of course titles wrapped in quotes
 */
function get_ordered_course_titles()
{
    $args = array(
        'post_type' => 'course',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'fields' => 'ids', // Get only the post IDs
    );

    $courses_query = new WP_Query($args);
    $courses = array();

    if ($courses_query->have_posts()) {
        foreach ($courses_query->posts as $course_id) {
            $course_title = get_the_title($course_id);
            $courses[] = '"' . esc_sql($course_title) . '"';
        }
    }
    wp_reset_postdata();

    return $courses;
}

// Add the filter for FacetWP ordering
add_filter('facetwp_facet_orderby', function ($orderby, $facet) {
    if ('product_course_finder' == $facet['name']) {
        $courses = get_ordered_course_titles();
        if (!empty($courses)) {
            $orderby = sprintf('FIELD(f.facet_display_value, %s)', join(', ', $courses));
        }
    }
    return $orderby;
}, 10, 2);

add_filter('facetwp_index_row', function ($params, $class) {
    if ('testimonial_category' == $params['facet_name']) {
        // Get all terms that need to be checked
        $terms = get_terms(array(
            'taxonomy' => 'testimonial_category', // Replace with your taxonomy name
            'hide_empty' => false,
        ));

        // Initialize array to store included terms
        $included_terms = array();

        // Loop through each term
        foreach ($terms as $term) {
            // Check if the term has the 'include' field and if it's set to true
            $should_include = get_field('archive_include', $term->taxonomy . '_' . $term->term_id);
            if ($should_include) {
                $included_terms[] = $term->name;
            }
        }

        // If the current facet value is NOT in our included terms, blank it out
        if (!in_array($params['facet_display_value'], $included_terms)) {
            $params['facet_value'] = '';
        }
    }

    return $params;
}, 10, 2);

// Recertification
add_filter('facetwp_facet_html', function ($output, $params) {
    if ('recertification' == $params['facet']['name']) {
        $facet = $params['facet'];
        $selected = $params['selected_values'];
        $checked = in_array('1', $selected) ? ' checked' : '';

        $output = '<div class="facetwp-facet facetwp-facet-' . $facet['name'] . '" data-name="' . $facet['name'] . '">';
        $output .= '<div class="facetwp-checkbox' . $checked . '" data-value="1">';
        $output .= '<span class="facetwp-display-value">Recertification Courses Only</span>';
        $output .= '</div>';
        $output .= '</div>';
    }
    return $output;
}, 10, 2);

add_action('wp_footer', function () {
    ?>
    <script>
    document.addEventListener('facetwp-loaded', function() {
        var tpl = fUtil('.wp-block-acf-find-a-course');
        if ('' != FWP.buildQueryString()) {
            tpl.addClass('facetwp-visible');
        } else {
            tpl.removeClass('facetwp-visible');
        }
    });


    (function($) {
    function toggleRecertificationOptions() {
        var $facet = $('.facetwp-facet-recertification');
        var $options = $facet.find('.facetwp-checkbox');

        if ($options.length === 2) {
            var $selectedOption = $facet.find('.facetwp-checkbox.checked');
            if ($selectedOption.length) {
                $options.not($selectedOption).hide();
            } else {
                $options.show();
            }
        }
    }

    $(document).on('facetwp-loaded', function() {
        toggleRecertificationOptions();

        $('.facetwp-facet-recertification').on('click', '.facetwp-checkbox', function() {
            setTimeout(toggleRecertificationOptions, 50);
        });
    });
})(jQuery);



(function($) {
    $(document).on('facetwp-refresh', function() {
        var dateRangeFacet = 'product_course_date_range';
        var dateMonthFacet = 'product_course_date_month';

        var facets = FWP.facets;

        // Check if date range facet is selected
        if (typeof facets[dateRangeFacet] !== 'undefined' && facets[dateRangeFacet].length > 0) {
            // Clear the month facet if it has a value
            console.log('dateRangeFacet is selected');

            if (facets[dateMonthFacet].length > 0) {
                FWP.facets[dateMonthFacet] = [];
                console.log('dateRangeFacet is selected, clearing month facet');
            }
        }
        // Check if month facet is selected
        if (typeof facets[dateMonthFacet] !== 'undefined' && facets[dateMonthFacet].length > 0) {
            console.log('dateMonthFacet is selected');

            // Clear the date range facet if it has a value
            if (facets[dateRangeFacet].length > 0) {
                FWP.facets[dateRangeFacet] = [];
                console.log('dateMonthFacet is selected, clearing date range facet');
            }
        }
    });
})(jQuery);

    </script>
    <?php
}, 100);

// Replace "my-daterange-facet" with the name of your Date Range facet
add_filter('facetwp_render_output', function ($output, $params) {

    // Calculate the min and max dates based on 5 years ago and 5 years from now
    $min_date = date('Y-m-d', strtotime('-2 years'));
    $max_date = date('Y-m-d', strtotime('+2 years'));

    // Modify the displayed date range in the date picker for the Start Date field:
    $output['settings']['product_course_date_range']['range']['min']['minDate'] = $min_date;
    $output['settings']['product_course_date_range']['range']['min']['maxDate'] = $max_date;

    // Modify the displayed date range in the date picker for the End Date field:
    $output['settings']['product_course_date_range']['range']['max']['minDate'] = $min_date;
    $output['settings']['product_course_date_range']['range']['max']['maxDate'] = $max_date;

    return $output;
}, 10, 2);

/* add_filter( 'facetwp_facet_dropdown_show_counts', function( $return, $params ) {
return false;
}, 10, 2 ); */

add_filter('facetwp_render_output', function ($output) {
    $facets = FWP()->helper->get_facets();
    foreach ($facets as $facet) {
        if ('fselect' == $facet['type']) {
            $output['settings'][$facet['name']]['showSearch'] = false;
        }
    }
    return $output;
});

function fwp_add_facet_labels()
{
    ?>
    <script>
      (function($) {
        $(document).on('facetwp-loaded', function() {
          $('.facetwp-facet').each(function() {
            var facet = $(this);
            var facet_name = facet.attr('data-name');
            var facet_type = facet.attr('data-type');
            var facet_label = FWP.settings.labels[facet_name];
            if ( ! ['pager','sort','reset'].includes( facet_type ) ) { // Add or remove excluded facet types to/from the array
              if (facet.closest('.facetwp-wrap').length < 1 && facet.closest('.facetwp-flyout').length < 1) {
                facet.wrap('<div class="facetwp-wrap"></div>');
                facet.before('<div class="facetwp-label facetwp-label-' + facet_name + '">' + facet_label + '</div>');
              }
            }
          });
        });
      })(jQuery);
    </script>
    <?php
}

add_action('wp_head', 'fwp_add_facet_labels', 100);

add_filter('facetwp_render_output', function ($output) {
    // Condition for the 'product_course_finder' facet
    if (isset($output['settings']['product_course_finder'])) {
        $output['settings']['product_course_finder']['overflowText'] = '{n} Selected';
        $output['settings']['product_course_finder']['numDisplayed'] = 0;
    }

    // Condition for the 'product_course_mode' facet
    if (isset($output['settings']['product_course_mode'])) {
        $output['settings']['product_course_mode']['overflowText'] = '{n} Selected';
        $output['settings']['product_course_mode']['numDisplayed'] = 0;
    }

    // Add more conditions for additional facets here as needed

    return $output;
});

add_filter('facetwp_facet_display_value', function ($label, $params) {
    if ('product_course_finder' == $params['facet']['name']) { // Replace "my_taxonomy_facet" with the name of your taxonomy-based facet
        $icon = get_field('course_icon', $params['row']['facet_value']);
        if (!empty($icon)) {
            $icon = be_icon([
                'icon' => $icon,
                'group' => 'courses',
                'size' => false,
                'force' => true,
            ]);

            if (!empty($icon)) {
                $label = sprintf('<span class="icon">%s</span><span class="label">%s</span>', $icon, $label);
            }
        }
    }
    return $label;
}, 10, 2);
