<?php

if (!function_exists('FWP')) {
    return;
}


add_action( 'wp_head', function() {
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
  }, 100 );



// Index the course category instead of post ID
add_filter('facetwp_index_row', function($params, $class) {
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
add_filter( 'facetwp_index_row', function( $params ) {
    if ( 'product_course_date_month' == $params['facet_name'] ) {
        $date = $params['facet_value'];
        $params['facet_value'] = date( 'F', strtotime( $date ) );
        $params['facet_display_value'] = $params['facet_value'];
    }
    return $params;
}, 10, 2 );


// Recertification
add_filter('facetwp_facet_html', function($output, $params) {
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


add_action( 'wp_footer', function() {
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
        $(document).on('facetwp-loaded', function() {
            var dateRangeFacet = 'product_course_date_range';
            var dateMonthFacet = 'product_course_date_month';
            
            var facets = FWP.facets;
            
            // Check if date range facet is selected
            if (facets[dateRangeFacet].length > 0) {
                // Clear the month facet if it has a value
                if (facets[dateMonthFacet].length > 0) {
                    FWP.reset(dateMonthFacet);
                    FWP.refresh();
                }
            }
            // Check if month facet is selected
            else if (facets[dateMonthFacet].length > 0) {
                // Clear the date range facet if it has a value
                if (facets[dateRangeFacet].length > 0) {
                    FWP.reset(dateRangeFacet);
                    FWP.refresh();
                }
            }


        });
    })(jQuery);
    </script>
    <?php
    }, 100 );

/* add_filter( 'facetwp_facet_dropdown_show_counts', function( $return, $params ) {
    return false;
}, 10, 2 ); */


add_filter( 'facetwp_render_output', function( $output ) {
    $facets = FWP()->helper->get_facets();
    foreach ( $facets as $facet ) {
        if ( 'fselect' == $facet['type'] ) {
            $output['settings'][ $facet['name'] ]['showSearch'] = false;
        }
    }
    return $output;
});


function fwp_add_facet_labels() {
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
 
add_action( 'wp_head', 'fwp_add_facet_labels', 100 );