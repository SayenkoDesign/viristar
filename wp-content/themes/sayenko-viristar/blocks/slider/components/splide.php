<?php

$post_ids = get_field('posts');

$args = array(
	'post_type' => 'slider',
	'order' => 'ASC',
	'orderby' => 'title',
	'posts_per_page' => -1,
);

if (!empty($post_ids)) {
	$args['orderby'] = 'post__in';
	$args['post__in'] = $post_ids;
	$args['posts_per_page'] = count($post_ids);
}




// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()) :

?>


<div class="splide" aria-label="Basic Structure Example">
  <div class="splide__track">
    <ul class="splide__list">
		<?php
		while ($loop->have_posts()) : $loop->the_post();
		?>
			<li class="splide__slide" data-title="<?php the_title();?>">
				<?php 
				the_post_thumbnail();
				the_content(); 
				?>
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