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

// Open the block
echo $block->before_render();

$title = get_field('title');
$day = get_field('day');
$description = wpautop(get_field('description'));

if(empty($day)) {
	printf('<div class="acf-block-placeholder"><h3>%s</h3><p>Click to Edit</p></div>', $block->get_title());
} else {


		echo '<div class="topics">';

		if(! empty($title)) {
			printf('<div class="day"></div><div class="topic-title"><h3>%s</h3></div>', $title);
		}
		

		printf('<div class="day"><h4>%s</h4></div><div>%s</div>', $day, $description);


		echo '</div>';
}


// close the block
echo $block->after_render();
?>
