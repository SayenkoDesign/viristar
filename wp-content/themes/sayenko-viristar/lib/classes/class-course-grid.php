<?php
/*
 * Course Grid
*/

namespace App;

class Course_Grid {
    
    public function __construct() {
        
    }

    function get_results( $args = [] ) {

        $today = date("Y-m-d H:i:s");
        $defaults = array(
            "post_type" => "product",
            "post_status" => 'publish',
            'posts_per_page' => '6',
            'meta_key' => 'start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'facetwp' => true
        );
        $defaults['meta_query'] = array(
            'relation' => "AND",
            array(
                'key' => 'start_date',
                'value' => $today,
                'compare' => '>=',
                'type' => 'DATETIME'
            )
    
        );

        $args = wp_parse_args( $args, $defaults );

        $loop = new \WP_Query($args);

        if ($loop->have_posts()) :

			while ($loop->have_posts()) : $loop->the_post();
                get_template_part('template-parts/course-item');
			endwhile;
        
        else: 
            printf('<div class="no-results"><h3>%s</h3></div>', __('We are sorry, but there are currently no courses that match your search criteria.', 'viristar'));  
        endif;
        wp_reset_postdata();
    }
    
}

new Course_Grid();