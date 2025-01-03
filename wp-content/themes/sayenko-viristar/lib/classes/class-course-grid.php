<?php
/*
 * Course Grid
 */

namespace App;

class Course_Grid
{
    public function __construct()
    {}

    public function get_results($args = [])
    {
        $current_time = time();

        // Get FacetWP filtered post IDs if they exist
        $post__in = null;
        if (function_exists('FWP')) {
            $post__in = FWP()->facet->query_args['post__in'] ?? null;
        }

        // Set up the base query args
        $defaults = array(
            "post_type" => "product",
            "post_status" => 'publish',
            'posts_per_page' => 24,
            'meta_key' => 'start_date_unix_timestamp',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'facetwp' => true,
            'meta_query' => array(
                // Check that the product is not out of stock
                array('relationship' => 'OR'),
                array(
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '=',
                ),
                array(
                    'key' => 'start_date_unix_timestamp',
                    'value' => $current_time,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ),
            ),
        );

        // If we have filtered post IDs, add them to the query
        if (!empty($post__in)) {
            $defaults['post__in'] = $post__in;
        }

        // Extract any additional meta_query from args and merge it with defaults
        if (!empty($args['meta_query']) && is_array($args['meta_query'])) {
            // Merge additional meta_query conditions with the defaults
            $defaults['meta_query'] = array_merge($defaults['meta_query'], $args['meta_query']);
            unset($args['meta_query']);
        }

        // Merge with any custom args - this preserves the facetwp parameter
        $query_args = wp_parse_args($args, $defaults);

        // Create the WP_Query with the merged args
        $loop = new \WP_Query($query_args);

        if ($loop->have_posts()):
            while ($loop->have_posts()): $loop->the_post();
                get_template_part('template-parts/course-item');
            endwhile;
        else:
            print('<div class="no-results"><h3>We are sorry, but there are currently no courses that match your search criteria.</h3><p><a href="javascript:;" onclick="FWP.reset()" class="gb-button">Search Again</a></p></div>');
        endif;
        wp_reset_postdata();
    }

    public function count_results($args = [])
    {
        $args['posts_per_page'] = -1;
        $args['fields'] = 'ids';

        $current_time = time();

        // We need to offset $today if we have a disable buy now button

        // Define the base query arguments
        $defaults = array(
            "post_type" => "product",
            "post_status" => 'publish',
            'facetwp' => true,
            'meta_query' => array(
                // Check that the product is not out of stock
                array('relationship' => 'OR'),
                array(
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '=',
                ),
                array(
                    'key' => 'start_date_unix_timestamp',
                    'value' => $current_time,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ),
            ),
        );

        // Extract any additional meta_query from args and merge it with defaults
        if (!empty($args['meta_query']) && is_array($args['meta_query'])) {
            // Merge additional meta_query conditions with the defaults
            $defaults['meta_query'] = array_merge($defaults['meta_query'], $args['meta_query']);
            unset($args['meta_query']);
        }

        // Merge with any additional custom args
        $query_args = wp_parse_args($args, $defaults);

        $query = new \WP_Query($query_args);

        return $query->found_posts;
    }

    public function get_post_titles($args = [])
    {
        if (isset($args['course'])) {
            $course = $args['course'];
            unset($args['course']);
        }

        $current_time = time();

        // Define the base query arguments
        $defaults = array(
            "post_type" => "product",
            "post_status" => 'publish',
            'facetwp' => true,
            'posts_per_page' => -1, // Retrieve all matching posts
            'meta_query' => array(
                // Check that the product is not out of stock
                array(
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '=',
                ),
                array(
                    'key' => 'start_date_unix_timestamp',
                    'value' => $current_time,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ),
            ),
        );

        // Extract any additional meta_query from args and merge it with defaults
        if (!empty($args['meta_query']) && is_array($args['meta_query'])) {
            // Merge additional meta_query conditions with the defaults
            $defaults['meta_query'] = array_merge($defaults['meta_query'], $args['meta_query']);
            unset($args['meta_query']);
        }

        // Merge with any additional custom args
        $query_args = wp_parse_args($args, $defaults);

        $query = new \WP_Query($query_args);

        // Retrieve the post titles
        $titles = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $titles[] = get_the_title();
            }
            wp_reset_postdata();
        }

        return $titles;
    }

}
