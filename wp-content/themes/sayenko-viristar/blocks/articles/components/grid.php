<?php
$block = $args['block'];

$args = array(
	'post_type' => 'post',
	'posts_per_page' => 2,
	'no_found_rows' => true,
);

// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()) :
?>
	<div class="grid">
		<?php
		while ($loop->have_posts()) : $loop->the_post();

			$index = $loop->current_post;

		?>
			<div class="grid__item">
				<div class="grid__thumbnail">
					<?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
				</div>
				<div class="grid__content">
					<?php
					//echo generate_do_post_meta_item('date');
					?>
					<h3><a href="<?php the_permalink(); ?>" class="link-cover"><?php the_title(); ?></a></h3>

					<div class="read-arrow-container"><span class="read-more">Read More</span></div>
				</div>
			</div>
		<?php

		endwhile;

		get_template_part(sprintf('blocks/%s/components/%s', $block->get_name(), 'form'), NULL, ['block' => $block] );

		?>
	</div>
<?php
endif;
wp_reset_postdata();
