
<?php
$full = $args['full'];

if (empty($full)) {
    return;
}

$image = $args['image'];
$logo = $args['logo'];
$details = $args['details'];
?>
<div class="modal testimonial-modal" id="testimonial-modal-<?php the_ID();?>" tabindex="-1" aria-labelledby="testimonialModal" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
		<div class="modal-header" data-bs-theme="dark">
		<h1 id="commitmentModal" class="visually-hidden">Testimonial</h1>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	<div class="modal-body">
	<?php

?>
<blockquote id="<?php the_ID();?>" <?php post_class();?> <?php generate_do_microdata('article');?>>
<div class="testimonial__content">
<?php
echo $image;

echo _s_format_string($full, 'div', ['class' => '']);

printf('<cite><p>%s</p>%s</cite>',
    get_the_title(),
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
			</div>
		</div>
	</div>

</div>