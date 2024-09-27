<?php
/**
 * Course Grid Template.
 */

use \App\Course_Grid;

$course_finder = new Course_Grid();
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

	/* $category = get_field('category');

	if(!empty($category)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',  // You can also use 'term_id' or 'name' here
				'terms'    => [$category],  // Replace with your actual category slug
			),
		);
	} */

	$course_finder->get_results( $args );
	?>
	</div>
</div>