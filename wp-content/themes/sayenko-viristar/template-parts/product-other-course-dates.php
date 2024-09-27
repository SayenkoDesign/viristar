<?php
/**
 * Course Grid Template.
 */

use \App\Course_Grid;

$course_finder = new Course_Grid();

echo '<div class="courses courses--grid">';
// get_template_part('template-parts/course-view');
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
			'location'
		];

		foreach( $list_meta as $key) {

			$header = str_replace('_', ' ', $key);

			if($key == 'month_year') {
				$header = 'Month/Year';

			}
			printf( '<div class="list-headers__%s">%s</div>', $key, $header);
		}
		?>
	</div>
	<div class="grid facetwp-template">
	<?php

	$args = [];

	$course_type = get_field( 'course_type', get_the_ID() );

	$args = array(
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'course_type',
				'value' => $course_type,
				'compare' => '='
			)
			// The existing 'start_date' condition from the function will be added to this
		)
	);
	
	$args['post__not_in'] = array( get_the_ID() );

	$course_finder->get_results( $args );
	?>
	</div>
</div>
<?php 
echo '</div>';