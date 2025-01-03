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

$post_id = get_field('post');

if (empty($post_id)) {
    return;
}

// Open the block
echo $block->before_render();

$args = array(
    'post_type' => 'team',
    'p' => $post_id,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
);

// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()):

    // $title = get_field('title') ?: 'Instructor';
    //  echo _s_format_string($title, 'h2');
    ?>
			<div class="instructor">
				<?php
    while ($loop->have_posts()): $loop->the_post();
        ?>
									<div>
										<?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
										<div class="instructor__details">
											<h3><?php echo get_the_title(get_the_ID()); ?></h3>
										<?php
        echo _s_format_string(get_field('position', get_the_ID()), 'div', ['class' => 'instructor__position']);

        ?>
										</div>
									</div>

									<div>
										<?php
        echo apply_filters('the_content', get_the_content());
        ?>
									</div>

							<?php
    endwhile;
    ?>
			</div>
		<?php
endif;
wp_reset_postdata();

echo $block->after_render();
