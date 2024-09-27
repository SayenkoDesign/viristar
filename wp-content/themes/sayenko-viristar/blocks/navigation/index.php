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

$menu_id = get_field('menu');
$menu_slug = get_menu_slug_from_id($menu_id);


if (!($is_preview)) {
    $block->add_render_attribute('class', 'frontend');
}

$block->set_html_tag('nav');

// Open the block
echo $block->before_render();



if ($menu_id) {
    // Get the menu slug from the menu ID
    $menu_slug = get_menu_slug_from_id($menu_id);
    $menu_items = wp_get_nav_menu_items($menu_id);

    if ($menu_items) {
        // Add the menu slug to the class attribute of the ul element
        echo sprintf('<ul class="menu menu-%s">', esc_attr($menu_slug));
        foreach ($menu_items as $menu_item) {
            $title = $menu_item->title;
            $url = $menu_item->url;
            $classes = join( ' ', $menu_item->classes);
            printf('<li class="%s"><a href="%s">%s</a></li>', esc_attr($classes), esc_url($url), esc_html($title));
        }
        echo '</ul>';
    }
}

// close the block
echo $block->after_render();