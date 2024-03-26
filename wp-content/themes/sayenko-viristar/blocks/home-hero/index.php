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

/*  echo '<pre>';
 var_dump($block);
 echo '</pre>'; */


use \App\ACF_Block;

$block = new ACF_Block($block, $content, $is_preview);

if(!($is_preview)) {
	$block->add_render_attribute('class', 'frontend');
	$block->add_render_attribute('class', 'alignfull');
}


// Open the block
echo $block->before_render();

$default_blocks = array(
    array('core/heading', array(
		'level' => 1,
		'placeholder' => 'Hero Heading',
	))
);
?>
<div class="hero">
<?php
get_template_part(sprintf('blocks/%s/components/%s', $block->get_name(), 'image'));
?>
<div class="hero__container">
	<div class="hero__content">
<InnerBlocks
		template="<?php echo esc_attr( wp_json_encode( $default_blocks ) ); ?>"
        className=""
    />
</div>

</div>
<?php
get_template_part(sprintf('blocks/%s/components/%s', $block->get_name(), 'wave'));
?>
</div>
<?php
// close the block
echo $block->after_render();