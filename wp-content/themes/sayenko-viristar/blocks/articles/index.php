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
?>

<div class="header">
	<?php
	$title = get_field('title');
	echo _s_format_string($title, 'h2', ['class' => '']);
	?>
	<div class="header__content">
		<?php
		
		the_field('text');
		$permalink = get_post_type_archive_link('post');
		?>
		<p class="header__link"><a href="<?php echo $permalink; ?>" class="link-arrow-icon">All Articles</a></p>
	</div>
</div>


<?php
get_template_part(sprintf('blocks/%s/components/%s', $block->get_name(), 'grid'), NULL, ['block' => $block]);
?>

<?php

// close the block
echo $block->after_render();
