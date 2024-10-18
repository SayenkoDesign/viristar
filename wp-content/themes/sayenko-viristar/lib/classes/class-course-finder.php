<?php
/*
 * Course Finder
*/

namespace App;

class Course_Finder {

    function init() {
        // Do some initial things here
        
    }

    function render() {

        ?>
        <div class="courses">
            <div class="courses__sidebar sidebar">
                <div class="sidebar__title" aria-hidden="true">
                    <h3>Find a Course</h3>
                </div>
                <div class="sidebar__content">
                <?php

                    $this->get_facets();
                ?>
                </div>
            </div>
            <div class="courses__list">
            <?php

            get_template_part('template-parts/course-view');
            get_template_part('template-parts/course-grid');

            if(function_exists('facetwp_display')) {
                echo facetwp_display('facet', 'pager_');
            }
            ?>
            </div>
        </div>
        <?php
    }


    function get_facets() {

        if(! function_exists('facetwp_display')) {
            return;

        }
        
        // echo facetwp_display('facet', 'product_course_category');

        echo facetwp_display('facet', 'product_course_finder');

        echo facetwp_display('facet', 'product_course_mode');

        $items = [];

        $items[] = [
			'title' => 'By Range',
			'content' => facetwp_display('facet', 'product_course_date_range')
		];

        $items[] = [
			'title' => 'By Month',
			'content' => facetwp_display('facet', 'product_course_date_month')
		];

        $tabs = new \App\Tabs($items);

        $tabs->render();


        echo facetwp_display('facet', 'product_course_location');


        echo facetwp_display('facet', 'recertification');

        echo '<a class="reset" href="javascript:;" onclick="FWP.reset()">Reset</a>';
                
    }

    function get_facet_misc() {

        if(! function_exists('facetwp_display')) {
            return;

        }

        echo facetwp_display('selections');
        echo '<a class="facetwp-reset" href="javascript:;" onclick="FWP.reset()">Clear</a>';
    }


    
    
}

new Course_Finder();