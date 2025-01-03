<?php
/*
 * Course Finder
 */

namespace App;

class Course_Finder
{

    function init()
    {
        // Do some initial things here

    }

    function render()
    {

        ?>
        <div class="courses">
            <div class="courses__sidebar sidebar offcanvas-lg offcanvas-start" data-bs-scroll="true" id="left-sidebar" tabindex="-1" aria-labelledby="offcanvasLeftsidebar">
                <div class="offcanvas-header">
                    <h3 class="offcanvas-title" id="offcanvasLeftsidebar">Filters</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#left-sidebar" aria-label="Close"></button>
                </div>

                <div class="sidebar__title">
                    <h3 aria-hidden="true">Find a Course</h3>
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

        echo facetwp_display('selections');

        get_template_part('template-parts/course-grid');

        if (function_exists('facetwp_display')) {
            echo facetwp_display('facet', 'pager_');
        }
        ?>
            </div>
        </div>
        <?php
}

    function get_facets()
    {

        if (!function_exists('facetwp_display')) {
            return;

        }

        echo facetwp_display('facet', 'product_course_finder');

        echo facetwp_display('facet', 'product_course_mode');

        $items = [];

        $items[] = [
            'title' => 'By Range',
            'content' => facetwp_display('facet', 'product_course_date_range'),
        ];

        $items[] = [
            'title' => 'By Month',
            'content' => '<p style="font-size: 13px;">Choose the month in which the course begins. <span id="clear-by-range-facet" class="facetwp-clear-button">Clear By Range</span>
</p>' . facetwp_display('facet', 'product_course_date_month'),
        ];

        $tabs = new \App\Tabs($items);

        $tabs->render();

        echo facetwp_display('facet', 'product_course_location');

        echo facetwp_display('facet', 'recertification');

        echo '<a class="reset" href="javascript:;" onclick="FWP.reset()">Reset</a>';

    }

    function get_facet_misc()
    {

        if (!function_exists('facetwp_display')) {
            return;

        }

        echo facetwp_display('selections');
        echo '<button class="reset">Clear</button>';
    }

}

new Course_Finder();