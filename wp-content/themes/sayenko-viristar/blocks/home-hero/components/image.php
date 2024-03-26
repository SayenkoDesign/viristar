<?php
$image = get_field('image');
if($image) {
	?>
	<div class="hero__background">
	<figure class="hero-image"> 
	<?php	
		echo wp_get_attachment_image( $image, 'wide');
	?>
	</figure>
	</div>
	<?php
}
?>