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

$block_classes[] = 'alignfull section-padding-bottom';

$block = new ACF_Block($block, $content, $is_preview);

$block->add_render_attribute([
    'class' => $block_classes,
]);

if ($is_preview) {
    echo '<h3>Find a Course</h3>';
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
    // Add the background here
    // Add the wave here
    printf('<div class="wave"><img src="%s/s-wave-shape-flipped.svg" /></div>', THEME_IMG);

    echo '<div class="wrapper">';
    echo '<div class="content-width">';
    echo '<h2 class="section-title">Find a Course</h2>';
    get_template_part('template-parts/course-filters');
    echo '<div class="courses courses--grid">';
    get_template_part('template-parts/course-view');
    echo $content;
    echo '</div>';
    echo '<p class="view-all"><a class="gb-button" href="/education/courses/find-a-course/">View All Courses</a></p>';
    echo '</div>';
    echo '</div>';
    echo $block->after_render();
}
