<?php
/**
 * Course Grid Template.
 */

use \App\Course_Grid;

$course_grid = new Course_Grid();

$args = [];

$course_type = get_field('course_type', get_the_ID());
$course = get_field('course', get_the_ID());

$_args = [
    'meta_query' => [
        [
            'key' => 'course',
            'value' => $course,
            'compare' => '=',
        ],
    ],
];

$_args['post__not_in'] = array(get_the_ID());

$count_results = $course_grid->count_results($_args);

if (empty($count_results)) {
    return '';
}

echo '<div class="courses courses--grid">';
get_template_part('template-parts/course-view');
?>
<div class="course-list">
	<div class="list-headers">
		<?php
$list_meta = [
    'course_name',
    'month_year',
    'start_date',
    // 'live_session_times',
    'tuition',
    'location',
];

foreach ($list_meta as $key) {

    $header = str_replace('_', ' ', $key);

    if ($key == 'month_year') {
        $header = 'Month/Year';

    }
    printf('<div class="list-headers__%s">%s</div>', $key, $header);
}
?>
	</div>
	<div class="grid facetwp-template">
	<?php

$course_grid->get_results($_args);
?>
	</div>
</div>
<?php
echo '</div>';