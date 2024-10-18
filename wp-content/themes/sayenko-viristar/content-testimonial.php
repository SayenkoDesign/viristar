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
<blockquote id="<?php the_ID(); ?>" <?php post_class();?> <?php generate_do_microdata('article');?>>
<div class="testimonial__content">
<?php

	
	echo _s_format_string( $teaser, 'div', ['aria-hidden' => true] );
	
	echo _s_format_string( $full, 'div', ['class' => 'visually-hidden'] );

	

	printf('<cite>%s<div><p>%s</p>%s</div></cite>',
		$image,
		get_the_title(get_the_ID()),
		$details
	);

	$arrow = _s_get_icon(
		[
			'icon'	=> 'link-arrow',
			'group'	=> 'theme',
			'class'	=> '',
			'width' => 8,
			'height' => 13,
			'label'	=> false,
		]
		);

	
	if( $word_count > $length ) {

		printf( '<p><a data-bs-toggle="modal" data-bs-target="#testimonial-modal-%s" aria-hidden="true" class="gb-button-link">%s<span>%s</span></a></p>', get_the_ID(), __('read more', 'viristar'), $arrow );
		
	}
	
?>
</div>
<?php


// Add the logo

echo _s_format_string( wp_get_attachment_image( $logo, 'thumbnail' ), 'div', ['class' => 'testimonial__logo'] );
?>

</blockquote>
