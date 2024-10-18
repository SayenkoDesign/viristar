<?php

$rows = get_field('rows');

if(empty($rows)) {
	return;
}


$data_splide = [
	'type' => 'loop',
	'perPage' => 5,
	'gap' => '4rem',
	'breakpoints' => [

		979 => [
			'perPage' => 3,
			'gap' => '2rem',
			'pagination' => false,
		],
		639 => [
			'perPage' => 2,
		]
	],
];


if( have_rows('rows') ):

?>

<div id="<?php echo wp_unique_id('splidejs-');?>" class="splide" data-splide="<?php echo esc_attr(json_encode($data_splide)); ?>" aria-label="Clients Slider">
  <div class="splide__track">
    <ul class="splide__list">
		<?php
		while( have_rows('rows') ): the_row(); 		
		?>
			<li class="splide__slide">
				<div class="logo">
				<?php
				$image = get_sub_field('image');
				echo wp_get_attachment_image( $image, 'medium' );
				?>
				</div>
			</li>
		<?php
		endwhile;
		?>
	  </ul>
  </div>
</div>
<?php
endif;