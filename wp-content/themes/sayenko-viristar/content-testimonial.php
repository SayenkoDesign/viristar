<?php
/**
 * The template for displaying posts within the loop.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$teaser = $args['teaser'];
$full = $args['full'];
$image = $args['image'];
$logo = $args['logo'];
$details = $args['details'];
$word_count = $args['word_count'];
$length = $args['length'];

?>
<blockquote id="<?php the_ID();?>" <?php post_class();?> <?php generate_do_microdata('article');?>>
<div class="testimonial__content">
<?php

echo $image;

$aria_hidden = empty($full) ? 'false' : 'true';

echo _s_format_string($teaser, 'div', ['aria-hidden' => $aria_hidden]);

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

if (!empty($full)) {

    printf('<p><a data-bs-toggle="modal" data-bs-target="#testimonial-modal-%s" aria-hidden="true" class="gb-button-link">%s<span>%s</span></a></p>', get_the_ID(), __('read more', 'viristar'), $arrow);

}

printf('<cite><p>%s</p>%s</cite>',
    get_the_title(get_the_ID()),
    $details
);

?>
</div>
<?php

// Add the logo
$logo = wp_get_attachment_image($logo, 'thumbnail');
$logo = _s_format_string($logo, 'div', ['class' => 'testimonial__logo-wrapper']);
echo _s_format_string($logo, 'div', ['class' => 'testimonial__logo']);
?>

</blockquote>
