<?php

/**
 * Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

use \App\ACF_Block;

$block = new ACF_Block($block, $content, $is_preview);

// Placeholder
if ($is_preview) {
	printf('<div class="acf-block-placeholder"><h3>%s</h3><p>Click to Edit</p></div>', $block->get_title());
	return;
}

// Open the block
echo $block->before_render();


$title = get_field('title');
echo _s_format_string($title, 'h2', ['class' => 'text-center']);

$post_ids = get_field('posts');

$args = array(
	'post_type' => 'team',
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
	<div class="flex-grid flex-grid-4 grid--gap-sm">
		<?php
		while ($loop->have_posts()) : $loop->the_post();
			$unique_id = wp_unique_id('team-member-');
		?>
			<div class="item">
				<div class="flex-grid__thumbnail">
					<?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
					<?php
					if (get_post_field('post_content', get_the_ID())) :
					?>
						<a class="link-cover" data-bs-toggle="modal" data-bs-target="#<?php echo $unique_id; ?>" aria-hidden="true"><span class="screen-reader-text">Read More</span></a>
					<?php
					endif;
					?>
				</div>
				<h3><?php echo get_the_title(get_the_ID()); ?></h3>
				<?php
				echo _s_format_string(get_field('position', get_the_ID()), 'div', ['class' => 'position']);
				?>
			</div>

			<?php
			if (get_post_field('post_content', get_the_ID())) :
			?>
				<div class="modal" id="<?php echo $unique_id; ?>" tabindex="-1" aria-labelledby="teamModal" aria-hidden="true" style="display: none;">
					<div class="modal-dialog modal-dialog-centered modal-xl">
						<div class="modal-content">
							<div class="modal-header">
								<h1 id="teamModal" class="visually-hidden">Team Modal</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="grid-2-3">
									<div class="team-item">
										<?php
										echo _s_format_string(get_the_post_thumbnail(get_the_ID(), 'medium'), 'div', ['class' => 'team-item__thumbnail']);

										echo _s_format_string(get_the_title(get_the_ID()), 'h4', ['class' => '']);
										echo _s_format_string(get_field('position', get_the_ID()), 'div', ['class' => 'position']);
										?>
									</div>
									<div>
										<?php
										the_content();
										?>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			<?php
			endif;
			?>

		<?php
		endwhile;
		?>
	</div>
<?php
endif;
wp_reset_postdata();

echo $block->after_render();
