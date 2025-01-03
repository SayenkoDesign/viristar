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
    $settings = [$block->get_class_name()];
}

$settings = ['alignfull', 'section-padding'];

$section_spacing = get_field('section_spacing');
$section_spacing = strtolower($section_spacing);
if ('yes' === $section_spacing) {
    $settings[] = 'section-spacing';
}

$background_color = get_field('background_color') ?: 'Dark Navy';
$background_color = strtolower(str_replace(' ', '-', $background_color));

$settings[] = 'bg--' . $background_color;

$contour_lines = get_field('contour_lines') ?: 'No';
$contour_lines = strtolower($contour_lines);

$block->add_render_attribute('class', $settings);

echo $block->before_render();

$title = get_field('title');
$title = _s_format_string($title, 'h2');
$text = get_field('text');
$text = _s_format_string($text, 'div', ['class' => 'text']);
$button = get_field('button') ?: [];
$button['classes'] = 'gb-button';
$button = _s_get_acf_button($button);
$button = _s_format_string($button, 'div', ['class' => 'button-wrapper']);

$classes = 'text-center';

if (!empty($title) || !empty($text)) {
    $classes = 'grid-2-cta';
}

if (!empty($title) && !empty($text)) {
    $classes = 'grid-3-cta';
}

printf('<div class="content-width %s">%s%s%s</div>', $classes, $title, $text, $button);

printf('<div class="overlay"><img src="%scontour-background.png" /></div>', trailingslashit(THEME_IMG));

// close the block
echo $block->after_render();
