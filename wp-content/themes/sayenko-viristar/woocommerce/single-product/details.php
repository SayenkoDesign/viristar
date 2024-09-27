<div class="course-description">
	<?php
	$course_type = get_field('course_type', get_the_ID()); // rmop, wm, custom
	
	$rm 	= 'rm' == $course_type ? true : false;
	$wm 	= 'wm' == $course_type ? true : false;
	$custom = 'custom' == $course_type ? true : false;



	$course = new \App\Course( get_the_ID());

	$course_id = $course->get_course_id();


	$product_id = get_the_ID(); // Assumes you're in the product loop
	$product = wc_get_product($product_id);

	$start_date = get_field('start_date', $product_id);	
	$end_date = get_field('end_date', $product_id);
	$registration_deadline = get_field('registration_deadline', $product_id);

	$price = $product->get_price();
	$cost = get_field('cost', $product_id);
	$external_product_url = get_field('external_product_url', $product_id);
	
	?>
	<h3>Course Overview</h3>
	<div class="course-description__details">
		<div>
			<?php 
			// Course Cohert
			if($rm):
			?>
			<h5>Course Cohert</h5>
			<ul>
			<li><?php echo ($start_date) ? date('F Y', strtotime($start_date)) : 'TBD';?></li>
			<li>Course # <?php echo $product->get_sku() ?: 'TBD';?></li>
			</ul>

			<?php
			endif;

			// DATE
			if ($start_date && $end_date) {
				$formatted_start = date('F j', strtotime($start_date));
				$formatted_end = date('F j', strtotime($end_date));
				
				printf("<h5>Dates</h5><p>%s - <br />%s</p>", $formatted_start, $formatted_end);
			}


			// Course Length

			$course_length = _s_format_string( get_field('course_length', $course_id), 'p' );

			if($course_length) {
				printf("<h5>Course Length</h5>%s", $course_length);
			}


			// Location
			$location = get_field('location', $product_id); // Post Object

			if(!empty($location)) {
				
				$location = _s_format_string( get_the_title($location), 'p' );

				printf("<h5>Location</h5>%s", $location);
			}

			if($rm) {
				// Certification
				$certification = _s_format_string( get_field('certification', $course_id), 'p' );

				if($certification) {
					printf("<h5>Certification</h5>%s", $certification);
				}
			}
			


			// Notes
			$notes = get_field('notes', $product_id);

			if(!empty($notes)) {
				printf("<h5>Notes</h5>%s", $notes);
			}

			// Host
			$host = get_field('host', $product_id);
			$host = _s_format_string( get_the_title($host), 'p' );
			if(!empty($host)) {
				printf("<h5>Course host</h5>%s", $host);
			}

			?>
		</div>
		<div>

			<?php
			// Deadline
			if ($registration_deadline) {
				$formatted_deadline = date('F j', strtotime($registration_deadline));
				
				printf("<h5>Registration deadline</h5><p>%s</p>", $formatted_deadline);
			}



			// Live Sessions
			$live_sessions = get_field('live_sessions', $product_id);

			if($live_sessions) {
				printf("<h5>Live Sessions</h5>%s", $live_sessions);
			}


			// Tuition

			$label = $rm ? 'Tuition' : 'Cost';


			// Cost
			if(! empty($cost)) {
				$cost_description = _s_format_string( get_field('cost_description', $product_id), 'p' );
				printf("<h5>%s</h5><p>%s</p>%s", $label, $cost, $cost_description);
			} else if($price) {
				printf("<h5>%s</h5><p>%s</p>", $label, get_woocommerce_currency() . ' ' . get_woocommerce_currency_symbol() . $product->get_price());
			}


			// Language
			$language =  _s_format_string( get_field('language', $product_id), 'p' );
			if($language) {
				printf("<h5>Language</h5>%s", $language);
			}


			if(!$rm) {
				// Certification
				$certification = _s_format_string( get_field('certification', $course_id), 'p' );

				if($certification) {
					printf("<h5>Certification</h5>%s", $certification);
				}
			}

			// Registration Handled By
			$registration_handled_by =  _s_format_string( get_field('registration_handled_by', $product_id), 'p' );
			if($registration_handled_by) {
				printf("<h5>Registration Handled By</h5>%s", $registration_handled_by);
			}

			// CEU's
			$ceus =  _s_format_string( get_field('ceus', $course_id), 'p' );
			if($ceus) {
				printf("<h5>CEU's</h5>%s", $ceus);
			}
			?>

		</div>

	</div>

	<?
	if($price && $course_id):
	?>
	<p class="full-course-details"><a class="link-arrow" href="<?php echo get_the_permalink($course_id);?>">Full Course Description</a></p>
	<?php
	endif;


	// Add to cart

	if($product->is_in_stock()) {
		if(! empty($external_product_url)) {
			printf('<div class="cart external"><a href="%s" class="gb-button" target="_blank">Enroll Now</a></div>', $external_product_url);
			echo _s_format_string( get_field('external_product_url_description', $product_id), 'p', ['class' => 'external-product-url-description'] );
	
		} else {
			woocommerce_template_single_add_to_cart();
		}
	}

	

	?>

	<?php
	$sessions = get_field('sessions', $product_id);

	if(! empty($sessions) ) {
		$list_items = nl_2_li($sessions);
		if(! empty($list_items)) {
			printf('<h3>Live Sessions</h3><ul class="live-sessions">%s</ul>', $list_items);
		}
	
		the_field('session_details', $product_id);
	
		// Need to open a modal, so we ned a CPT for modals
		$time_zone_modal = get_field('time_zone_modal', 'option');
		if(! empty($time_zone_modal)) :
			$button = _s_get_acf_button( array( 'classes' => 'link-arrow', 'url' => get_permalink($time_zone_modal), 'title' => 'Time Zone Conversion Chart' ) );
			printf('<p class="time-zone">%s</p>', $button);
		endif;
	}
	
	?>


	

	<?php 
	$show_host_course = get_field('show_host_course', 'option');
	if(!empty($show_host_course)): // make this a checkbox in the product fields
	?>
	<p class="host-course"><a class="link-arrows" href="/host-a-course/">Host A Course</a></p>
	<?php 
	endif;
	?>
</div>