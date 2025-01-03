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
use \App\Course_Grid;

$block = new ACF_Block($block, $content, $is_preview);

// Placeholder
if ($is_preview) {
    printf('<div class="acf-block-placeholder"><h3>%s</h3><p>Click to Edit</p></div>', $block->get_title());
    return;
}

$arrow = _s_get_icon(
    [
        'icon' => 'link-arrow',
        'group' => 'theme',
        'class' => '',
        'width' => 8,
        'height' => 13,
        'label' => false,
    ]
);

// Open the block
echo $block->before_render();

$post_ids = get_field('posts');

$args = array(
    'post_type' => 'course',
    'order' => 'ASC',
    'orderby' => 'title',
    'no_found_rows' => true,
    'ignore_custom_sort' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'posts_per_page' => -1,
);

if (!empty($post_ids)) {
    $args['orderby'] = 'post__in';
    $args['post__in'] = $post_ids;
    $args['posts_per_page'] = count($post_ids);
}

// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()):

?>
    <div class="course-grid">
        <?php
while ($loop->have_posts()): $loop->the_post();

    $title = get_the_title(get_the_ID());

    $text = get_field('course_type_offered_block_text', get_the_ID());

    $course_finder_title = 'find available dates';
    $course_finder_url = get_field('course_finder_url', get_the_ID());
    $course_finder_link = '';

    $course_grid = new Course_Grid();

    $_args = [
        'meta_query' => [
            [
                'key' => 'course',
                'value' => get_the_ID(),
                'compare' => '=',
            ],
        ],
    ];

    $found = $course_grid->count_results($_args);

    if ($found) {
        $default_filter_url = sprintf('%s?_product_course_finder=%d', get_permalink(612), get_the_ID());
        $url = !empty($course_finder_url) ? $course_finder_url : $default_filter_url;
        $course_finder_link = sprintf('<div><a href="%s" class="gb-button">%s</a></div>', $url, $course_finder_title);
    }

    $permalink = get_the_permalink(get_the_ID());
    $permalink = sprintf('<a href="%s">%s</a>', $permalink, $title);
    ?>
			            <div class="item course-grid__item">

			                <div class="course-grid__image">
			                    <?php
    printf('<span class="course-grid__category">%s</span>', $title);
    $course_type_image = get_field('course_type_image', get_the_ID());
    if (empty($course_type_image)) {
        $course_type_image = get_post_thumbnail_id(get_the_ID());

    }
    echo wp_get_attachment_image($course_type_image, 'medium');
    ?>
			                </div>

			                <div class="course-grid__content">
			                    <?php
    echo $text;
    ?>
			                </div>
			                <div class="course-grid__buttons">
			                    <div><a href="<?php echo get_the_permalink(get_the_ID()); ?>" class="gb-button-link">Learn More <span><?php echo $arrow; ?></span></a></div>
			                    <?php

    echo $course_finder_link;

    ?>
			                </div>
			            </div>
			        <?php
endwhile;
?>

    <?php
endif;
wp_reset_postdata();

echo $block->after_render();
