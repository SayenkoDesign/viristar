
<div class="modal testimonial-modal" id="testimonial-modal-<?php the_ID(); ?>" tabindex="-1" aria-labelledby="testimonialModal" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
		<div class="modal-header" data-bs-theme="dark">
		<h1 id="commitmentModal" class="visually-hidden">Testimonial</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	<div class="modal-body">
	<?php

?>
<blockquote id="<?php the_ID(); ?>" <?php post_class();?> <?php generate_do_microdata('article');?>>
<div class="testimonial__content">
<?php

	$teaser = $args['teaser'];
	$full = $args['full'];
	$image = $args['image'];
	$logo = $args['logo'];
	$details = $args['details'];
	$word_count = $args['word_count'];
	$length = $args['length'];

	echo _s_format_string( $full, 'div', ['class' => ''] );

	printf('<cite>%s<div><p>%s</p>%s</div></cite>',
		$image,
		get_the_title(),
		$details
	);

	
?>
</div>
<?php


// Add the logo

echo _s_format_string( wp_get_attachment_image( $logo, 'thumbnail' ), 'figure', ['class' => 'testimonial__logo'] );
?>

</blockquote>
			</div>
		</div>
	</div>

</div>