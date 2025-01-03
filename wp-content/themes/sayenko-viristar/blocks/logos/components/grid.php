<?php
$block = $args['block'];

$images = get_field('images');

$image_size = 'thumbnail';

if ($images):
    echo '<div class="grid-logos">';
    foreach ($images as $image):
        $logo = wp_get_attachment_image($image, $image_size);
        echo _s_format_string($logo, 'div', ['class' => 'grid-logos__item']);
    endforeach;
    echo '</div>';
endif;
