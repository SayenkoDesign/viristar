<?php

$post_ids = get_field('posts');

$args = array(
	'post_type' => 'client',
	'order' => 'ASC',
	'orderby' => 'title',
	'posts_per_page' => 10,
);

if (!empty($post_ids)) {
	$args['orderby'] = 'post__in';
	$args['post__in'] = $post_ids;
	$args['posts_per_page'] = count($post_ids);
}


$data_splide = [
	'type' => 'loop',
	'perPage' => 5,
	'gap' => '4rem',
	'breakpoints' => [

		979 => [
			'perPage' => 3,
			'gap' => '2rem',
			'pagination' => false,
		],
		639 => [
			'perPage' => 2,
		]
	],
];

// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()) :

?>

<div id="<?php echo wp_unique_id('splidejs-');?>" class="splide" data-splide="<?php echo esc_attr(json_encode($data_splide)); ?>" aria-label="Clients Slider">
  <div class="splide__track">
    <ul class="splide__list">
		<?php
		while ($loop->have_posts()) : $loop->the_post();		
		?>
			<li class="splide__slide">
				<div class="logo">
				<?php
				the_post_thumbnail('medium');
				?>
				</div>
			</li>
		<?php
		endwhile;
		?>
	  </ul>
  </div>
   <div class="autoplay-controls" style="display: none;">
		<button class="my-toggle-button" type="button">Pause</button>
  </div>
</div>
<?php
endif;
wp_reset_postdata();