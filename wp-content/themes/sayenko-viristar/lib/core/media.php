<?php
// Image Sizes, adjust as needed

$image_sizes = [

    1920, // Hero

];

// Don't Touch below this line

foreach ($image_sizes as $size) {
    add_image_size(sprintf('image-%s', $size), $size, 9999);
}

/* add_filter('image_size_names_choose', function ($default_sizes) use($image_sizes) {

foreach($image_sizes as $size) {
$image_sizes_named[sprintf('image-%d', $size)] = sprintf('Image %d', $size);
}

return array_merge($default_sizes, $image_sizes_named);
}); */
