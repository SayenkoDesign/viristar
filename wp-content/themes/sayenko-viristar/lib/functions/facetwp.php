<?php

if (!function_exists('FWP')) {
    return;
}

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

// We need the product start date as a month
add_filter('facetwp_index_row', function ($params) {
    if ('product_course_date_month' == $params['facet_name']) {
        $date = $params['facet_value'];
        $params['facet_value'] = date('nY', strtotime($date));
        $params['facet_display_value'] = date('F Y', strtotime($date));
    }
    return $params;
}, 10, 2);

// This tricks it show the start and end date.

add_filter('facetwp_index_row', function ($params, $class) {
    if ('product_course_date_month_end' == $params['facet_name']) {
        $params['facet_name'] = 'product_course_date_month';
        $date = $params['facet_value'];
        $params['facet_value'] = date('nY', strtotime($date));
        $params['facet_display_value'] = date('F Y', strtotime($date));
    }
    return $params;
}, 10, 2);

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
        'no_found_rows' => true,
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
    if ('product_course' == $facet['name']) {
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

/** Resets a facet changing another facet
 ** change 'facet_name_1' and 'facet_name_2' to the names of your facets
 **/
add_action('wp_head', function () {?>
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

        // Get the button by its ID
        var clearButton = document.getElementById('clear-by-range-facet');

        // Add a click event listener to the button
        clearButton.addEventListener('click', function() {
            // Clear the specific facet by setting its value to an empty array
            FWP.reset('product_course_date_range');
        });
    });
})(jQuery);

    document.addEventListener('facetwp-refresh', function() {
        if ( null !== FWP.active_facet && 'product_course_date_range' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
            FWP.facets['product_course_date_month'] = [];
        }
    });
    </script>
<?php }, 100);

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

    // Condition for the 'testimonial_category' facet
    if (isset($output['settings']['testimonial_category'])) {
        $output['settings']['testimonial_category']['overflowText'] = '{n} Selected';
        $output['settings']['testimonial_category']['numDisplayed'] = 0;
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
