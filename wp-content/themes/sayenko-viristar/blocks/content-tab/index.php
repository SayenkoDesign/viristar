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

$default_blocks = array(
    
);

$title =  get_field('title');

if($is_preview):
?>
<h3 class="tab-title"><?php echo $title ? $title : 'Enter Tab Title in Block Sidebar';?></h3>
<?php
endif;
?>
<!-- acf-block-data <?php echo wp_json_encode(['title' => esc_attr($title)]);?> -->
<InnerBlocks
		template="<?php echo esc_attr( wp_json_encode( $default_blocks ) ); ?>"
        className="tab-container-content"
    />
<!-- /acf-block-data -->
<?php