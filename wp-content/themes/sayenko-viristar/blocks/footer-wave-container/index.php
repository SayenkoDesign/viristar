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

$settings = ['alignfull', 'section-padding-top'];

$background_color = get_field('background_color') ?: 'Dark Navy';
$background_color = strtolower(str_replace(' ', '-', $background_color));

$settings[] = 'bg--' . $background_color;

$section_spacing = get_field('section_spacing') ?: 'No';
$section_spacing = strtolower($section_spacing);
if ('yes' === $section_spacing) {
    $settings[] = 'section-spacing';
}

$contour_lines = get_field('contour_lines') ?: 'No';
$contour_lines = strtolower($contour_lines);

if ('navy' === $background_color) {
    // $contour_lines = 'no';
}

$block->set_render_attribute('class', $settings);

echo $block->before_render();

$default_blocks = array(

);

?>
<InnerBlocks
		template="<?php echo esc_attr(wp_json_encode($default_blocks)); ?>"
        className="<?php echo $block->get_class_name(); ?>__content"
    />
<?php

if (!$is_preview):

    if ('yes' === $contour_lines) {
        printf('<div class="overlay"><img src="%scontour-background.png" /></div>', trailingslashit(THEME_IMG));
    }

    // Output the wave

    printf('<div class="wave"><img src="%sfooter.svg" /></div>', trailingslashit(THEME_IMG));

endif;

// close the block
echo $block->after_render();
