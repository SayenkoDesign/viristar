<?php
$image = get_field('image');
$image_position = [];
$image_position_x = get_field('image_position_x');
$image_position_y = get_field('image_position_y');
$image_styles = '';

$image = get_field('image');
$image_position = [];
$image_position_x = get_field('image_position_x');
$image_position_y = get_field('image_position_y');
$image_styles = '';

if (is_numeric($image_position_x)) {
    $image_position[] = '--image-position-x: ' . $image_position_x . ($image_position_x == 0 ? '' : '%') . ';';
}
if (is_numeric($image_position_y)) {
    $image_position[] = '--image-position-y: ' . $image_position_y . ($image_position_y == 0 ? '' : '%') . ';';
}

if (!empty($image_position)) {
    $image_styles = ' style="' . join(' ', $image_position) . '"';
}



if($image) {
	?>
	<div class="hero__background"<?php echo $image_styles;?>>
	<figure class="hero-image"> 
	<?php	
		echo wp_get_attachment_image( $image, 'wide');
	?>
	</figure>
	</div>
	<?php
}
?>