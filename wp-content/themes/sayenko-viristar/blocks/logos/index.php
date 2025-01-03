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

$block_classes = [];

$block_classes[] = $alignment;

$block_classes[] = $layout;

$block->add_render_attribute([
    'class' => $block_classes,
]);

$args = ['block' => $block];

if ($is_preview):
?>
    <span class="preview-label">Click to edit <?php echo ucwords($block->get_readable_name()); ?> Block</span>
    <?php
else:

    // Open the block
    echo $block->before_render();

    get_template_part(sprintf('blocks/%s/components/%s', $block->get_name(), 'grid'), null, $args);

    // close the block
    echo $block->after_render();

endif;