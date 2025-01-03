<?php
/**
 * Course Grid Template.
 */

use \App\Course_Grid;

$course_grid = new Course_Grid();

if (empty($args)) {
    $args = [];
}

// is this a single product
if (is_singular('product')) {
    $post_id = get_the_ID();
    $args['post__not_in'] = array($post_id);
    $course_type = get_field('course', $post_id);
} else {
    $course_type = get_field('course_type');
}

if (!empty($course_type)) {
    $args['meta_query'] = array(
        'relation' => 'AND',
        array(
            'key' => 'course',
            'value' => $course_type,
            'compare' => '=',
        ),
        // The existing 'start_date' condition from the function will be added to this
    );

}

$results = $course_grid->count_results($args);

if (empty($results) && !is_page_template('page-templates/find-course.php')) {
    return;
}

?>
<div class="course-list">
	<div class="list-headers">
		<?php
$list_meta = [
    'course_name',
    'month_year',
    'start_date',
    'cost',
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
$course_grid->get_results($args);
?>
	</div>
</div>
