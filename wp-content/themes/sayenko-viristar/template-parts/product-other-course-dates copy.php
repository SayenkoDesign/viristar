<?php
/**
 * Course Grid Template.
 */

use \App\Course_Grid;

$course_grid = new Course_Grid();
?>
	
	<div class="grid facetwp-template">
	<?php

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

	$course_grid->get_results( $args );
	?>
	</div>
