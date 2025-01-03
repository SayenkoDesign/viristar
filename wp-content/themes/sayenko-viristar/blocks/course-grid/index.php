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

if ($is_preview) {
    printf('<div class="acf-block-placeholder"><h3>%s</h3><p>Click to Edit</p></div>', $block->get_title());
    return;
}

// Start output buffering
ob_start();

get_template_part('template-parts/course-grid');

// Get the buffered content and clean the buffer
$content = ob_get_clean();

// Check if both title and content exist before outputting
if (!empty($content)) {
    echo $block->before_render();
    echo '<div class="courses courses--grid">';
    get_template_part('template-parts/course-view');
    echo $content;
    echo '</div>';
    echo $block->after_render();
}
