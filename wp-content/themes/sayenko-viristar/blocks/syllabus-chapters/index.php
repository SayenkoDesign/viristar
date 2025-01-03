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

// Open the block
echo $block->before_render();

$chapter_title = get_field('chapter_title');

$chapters = get_field('chapters');

if (empty($chapter_title) && empty($chapters)) {
    printf('<div class="acf-block-placeholder"><h3>%s</h3><p>Click to Edit</p></div>', $block->get_title());
} else {

    $hide_chapters = false;

    $title = get_field('title');
    $time = get_field('time');
    if (!empty($time)) {
        $time = sprintf(' <em>%s</em>', $time);
    }

    $title = $title . $time;

    echo _s_format_string($title, 'h3');

    $chapter_description = get_field('chapter_description');

    if (!empty($chapter_description)) {
        $hide_chapters = true;

        echo '<div class="chapter-grid"><div>';
    }

    echo _s_format_string($chapter_title, 'h4');

    if (have_rows('chapters')):

        echo '<div class="chapters">';

        // Loop through rows.
        while (have_rows('chapters')): the_row();

            // Load sub field value.
            $title = get_sub_field('title');
            $chapter = get_sub_field('chapter') ? sprintf(' <span>%s</span>', get_sub_field('chapter')) : '';

            if (true === $hide_chapters) {
                $chapter = '';
            }

            if (empty($title)) {
                continue;
            }

            printf('<div class="chapter-title">%s</div><div class="chapter-numbers">%s</div>', $title, $chapter);

            // End loop.
        endwhile;

        echo '</div>';

    endif;

    if (!empty($chapter_description)) {

        echo '</div>';

        echo '<div class="chapter-description">' . $chapter_description . '</div>';

        echo '</div>';
    }

}

// close the block
echo $block->after_render();
