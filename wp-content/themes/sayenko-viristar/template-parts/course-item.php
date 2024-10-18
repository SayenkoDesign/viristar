<?php
$product_id = get_the_ID();

$course_object = new \App\Course( $product_id );

$course_name = $course_object->get_title();
$course_category = $course_object->get_category('slug');



$start_date = get_field('start_date', $product_id);
$end_date = get_field('end_date', $product_id);

$host = get_field('host', $product_id);
if(!empty($host)) {
	$host = get_the_title($host);
}

$modes = get_post_meta($product_id, 'modes', true);

$global_timezone = get_post_meta($product_id, 'timezone', true);

$add_to_cart = site_url() . "/cart/?add-to-cart=" . $product_id;
$_product = wc_get_product($product_id);

// Date
$month_year =  vs_course_month_year($product_id);
$start_date = vs_course_start_date($product_id);
$dates = vs_course_date($product_id);

$location = get_field('location', $product_id);
if(! empty($location)) {
	$location = get_the_title($location);
}

$live_sessions = get_field('live_sessions', $product_id);
$live_sessions = strip_tags(str_replace('<br />', ', ', $live_sessions));
$live_session_times = 'Live Sessions';

$instructor = get_field('instructor', $product_id);
if(! empty($instructor)) {
	$instructor = get_the_title($instructor);
}

$time_zone_modal = get_field('time_zone_modal', 'option');
if(! empty($time_zone_modal)) :
	$button = _s_get_acf_button( array( 'classes' => 'link-arrow', 'url' => get_permalink($time_zone_modal), 'title' => 'Time Zone Conversion Chart' ) );
	$time_zone_modal = sprintf('<p class="time-zone">%s</p>', $button);
endif;


// Tuition

$tuition = get_woocommerce_currency() . ' ' . get_woocommerce_currency_symbol() . $_product->get_price();
$cost = get_field('cost', $product_id);
if(! empty($cost)) {
	$tuition = $cost;
}

$external_product_url = get_field('external_product_url', $product_id);

if( 'risk-management' == $course_category) {
	$grid_meta = [
		'dates',
		'live_sessions',
		'tuition',
		'location'
	];

	$list_meta = [
		//'course_name',
		'month_year',
		'start_date',
		'live_sessions',
		'tuition',
		'location'
	];
} else {
	$grid_meta = [
		'dates',
		'tuition',
		'location',
		'instructor',
		'host'
	];

	/* $list_meta = [
		'month_year',
		'start_date',
		'tuition',
		'location',
		'instructor',
		//'host'
	]; */

	$list_meta = [
		//'course_name',
		'month_year',
		'start_date',
		// 'live_session_times',
		'tuition',
		'location'
	];
}



$arrow = _s_get_icon(
	[
		'icon'	=> 'link-arrow',
		'group'	=> 'theme',
		'class'	=> '',
		'width' => 8,
		'height' => 13,
		'label'	=> false,
	]
	);
?>
<div class="grid__item <?php echo $course_category;?>">

		<div class="grid__image">

			<?php 
				echo get_the_post_thumbnail($product_id, 'medium'); 
				echo _s_format_string($course_name, 'span', ['class'=>'grid__category']);
			?>			
		</div>
		
		<div class="grid__content">
			<h3 class="grid__title">
				<a href="<?php echo get_the_permalink($product_id); ?>">
					<?php echo get_the_title($product_id); ?>
				</a>
			</h3>


			<?php
			foreach( $grid_meta as $key) {
				$extras = '';
				$value = ${$key};

				if( 'live_sessions' == $key && ! empty($time_zone_modal)) {
					$extras = $time_zone_modal;
				}


				if(! empty( $value) ) {
					printf( '<div class="grid__%s"><span class="label">%s:</span> %s %s</div>', $key, str_replace('_', ' ', $key), $value, $extras );
				}
			}
			?>
</div>
		
		<div class="grid__buttons">

			<div><a href="<?php echo get_the_permalink($product_id); ?>" class="gb-button-link">Learn More <span><?php echo $arrow;?></span></a></div>

			<?php
			if(! empty($external_product_url)) {
				$add_to_cart = $external_product_url;
		
			}

			printf('<a href="%s" class="gb-button">Enroll Now</a>', $add_to_cart);
			?>
			
		</div>
</div>


<div class="list__item <?php echo $course_category;?>">

<div class="list__item-content">
		
	<div class="list__title">
		<p><a href="<?php echo get_the_permalink($product_id); ?>">
			<?php echo get_the_title($product_id); ?>
		</a></p>
	</div>
	

	<?php

	$live_sessions_data = '';

	foreach( $list_meta as $key) {
		$value = ${$key} ?? '';

		if( 'live_sessions' == $key ) {
			$live_sessions_data = $value;
			continue;
		}

		printf( '<div class="list__%s">%s</div>', $key, $value);
	}
	?>

	</div>

	<?php 
	if( !empty($live_sessions_data) && ! empty($time_zone_modal)) {
		
		echo _s_format_string($live_sessions_data, 'p' );;
		echo $time_zone_modal;
	}
	?>

	<div class="list__buttons">

			<?php
			if(! empty($external_product_url)) {
				$add_to_cart = $external_product_url;
			}

			printf('<div><a href="%s" class="gb-button">Enroll Now</a></div>', $add_to_cart);
			?>
			
			<div><a href="<?php echo get_the_permalink($product_id); ?>" class="gb-button-link">Learn More <span><?php echo $arrow;?></span></a></div>
		</div>
			
</div>