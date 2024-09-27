<?php
namespace App;

class Course_Filters {

    /**
     * Initialize the element
     */
    function __constuct() {
       

    }

    /**
     * Render the element
     * 
     * @param array $options
     * @param array $defaults
     * @param string $content
     */
    function render() {
        
        $this->get_facets();
    }

    
    /**
     * Get the facet filters for the course filters
     * 
     * @param array $options
     * @return string
     */
    function get_facets() {

        ?>
        <div class="filters">
        
        <?php

        echo facetwp_display('facet', $options['course_category']);

        echo facetwp_display('facet', $options['course']);

        echo facetwp_display('facet', $options['course_mode']);


        ?>
        <div class="facetwp-submit"><button class="fwp-submit"><i class="fa fa-search"></i> <span>Search</span></button></div>
        </div>
        <div class="facetwp-template" style="display:none">
		<?php
		    $this->get_post_ids(true);
        ?>
        </div>
        <?php
    }

    /**
     * Get the post IDs for the course filters
     * 
     * @param boolean $facetwp
     * @return array
     */
    function get_post_ids($facetwp = false ) {
        $today = date("Ymd");
        $args = array(
            "post_type" => "product",
            "post_status" => 'publish',
            'posts_per_page' => '100',
            'meta_key' => 'course_instance_start_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'fields' => 'ids'
        );

        if($facetwp) {
            $args['facetwp'] = true;
        }

        $args['meta_query']	= array(
            'relation' => "AND",
            array(
                'key' => 'course_instance_start_date',
                'value' => $today,
                'compare' => '>='
            ),
            array(
                'key' => 'product_type',
                'value' => 'Course Instance',
                'compare' => '='
            ),
            array(
                'key' => 'status',
                'value' => array('Open'),
                'compare' => 'IN'
            ),
        );

        $loop = new WP_Query($args);

        // Initialize an empty array to store post IDs
        $post_ids = array();

        // Loop through the query results
        if ($loop->have_posts()) {
            while ($loop->have_posts()) {
                $loop->the_post();
                get_template_part('template-parts/course-item');
            }
            // Restore original post data
            wp_reset_postdata();
        }

        // Return the array of post IDs
        return $post_ids;
    }

    
    
}

new CourseFiltersElement();