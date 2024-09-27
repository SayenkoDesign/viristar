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

/* $block->add_render_attribute(
	'block', 'class', [
	   'alignfull',
		]
	);
 */

// Placeholder
if ($is_preview) {
	printf('<div class="acf-block-placeholder"><h3>%s</h3><p>Click to Edit</p></div>', $block->get_title());
	return;
}

// Open the block
echo $block->before_render();


	$items = [];
	?>

	<?php if( have_rows('rows') ): ?>
		<?php while( have_rows('rows') ): the_row(); 

			$items[] = [
				'title' => get_sub_field('title'),
				'content' => get_sub_field('text')
			];
		endwhile; ?>
	<?php endif; ?>

<?php		

$accordion = new \App\Accordion($items, ['accordion_header_size' => 'h3']);

$accordion->render();

// close the block
echo $block->after_render();
?>
