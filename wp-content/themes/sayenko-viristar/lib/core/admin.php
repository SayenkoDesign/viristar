<?php
function my_login_logo() { 

	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	?>
		<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(<?php echo $image[0];?>); 
			width: 220px;
			height: 46px;
			background-size: contain;
			background-repeat: no-repeat;
			padding-bottom: 30px;
		}
		</style>
	<?php 
	}
	add_action( 'login_enqueue_scripts', 'my_login_logo' );