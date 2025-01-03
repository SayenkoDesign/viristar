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

// Placeholder
if ($is_preview) {
    printf('<div class="acf-block-placeholder"><h3>%s</h3></div>', 'Product Details - view the product live to see the fields');
    return;
}

/* $course = new \App\Course( get_the_ID() );

$course_category = $course->get_category('slug'); */

// wc_get_template_part('single-product/details/' . $course_category);

if (is_product()) {
    wc_get_template_part('single-product/details');
}
