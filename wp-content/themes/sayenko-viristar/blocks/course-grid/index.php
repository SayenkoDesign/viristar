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

if($is_preview) {
	echo '<h3>course Grid</h3>';	
	return;
}

// Open the block
echo $block->before_render();

get_template_part('template-parts/course-grid');

// close the block
echo $block->after_render();