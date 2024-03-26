<?php
// Image Sizes, adjust as needed

$image_sizes = [
	// 320, // WP Set as Thumbnail
	480,
	// 640, 
	//768, // set as WP Medium
	// 980,
	1024,
	// 1280, // Set as WP Large
	//1440,
	1920 // Hero

];

// Don't Touch below this line

foreach($image_sizes as $size ) {
	add_image_size(sprintf('image-%s', $size ), $size, 9999);
}


/* add_filter('image_size_names_choose', function ($default_sizes) use($image_sizes) {

	foreach($image_sizes as $size) {
		$image_sizes_named[sprintf('image-%d', $size)] = sprintf('Image %d', $size);
	}

	return array_merge($default_sizes, $image_sizes_named);
}); */