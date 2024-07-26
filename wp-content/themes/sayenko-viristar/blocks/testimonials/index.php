<?php

/**
 * Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

use \App\ACF_Block;

$block = new ACF_Block($block, $content, $is_preview);


// Open the block
echo $block->before_render();


get_template_part(sprintf('blocks/%s/components/%s', $block->get_name(), 'splide'));

if(!$is_preview):

	$number_of_testimonials = get_field('number_of_testimonials') ?: 3;
?>
<script>
(function (document, window, $) {


	const splide = new Splide("#<?php echo $block->get_id();?> .splide", {
		type: "loop",
		autoplay: false, // Enable autoplay
		rewind: false, // Enable infinite loop
		speed: 500,
		interval: 3000, // Set autoplay interval (3 seconds in this example)
		pauseOnHover: true,
		pauseOnFocus: true,
		pagination: true,
		arrows: true,
		//height: "auto",
		//autoHeight: true,
		perPage: <?php echo $number_of_testimonials;?>,
		perMove: 1,
		//gap: '1rem',
		breakpoints: {
			1500: {
				arrows: false,
			},
			1023: {
				perPage: 2,
				//gap: '1rem',
			},
			639: {
				perPage: 1,
				//gap: '0',
			},
		},
		});
  		  

	// hide paginaiton and arrows if not needed
	splide.on( 'overflow', function ( isOverflow ) {
	// Reset the carousel position
	splide.go( 0 );

	splide.options = {
		arrows    : isOverflow,
		pagination: isOverflow,
		drag      : isOverflow,
		clones    : isOverflow ? undefined : 0, // Toggle clones
	};
	} );
	  
	  
	splide.mount();
	  
	  
	  /* var toggleButton = splide.root.querySelector( '.my-toggle-button' );
	  
	  splide.on( 'autoplay:play', function () {
		toggleButton.setAttribute( 'aria-label', 'Pause autoplay' );
		toggleButton.textContent = 'Pause';
	  } );
	  
	  splide.on( 'autoplay:pause', function () {
		toggleButton.setAttribute( 'aria-label', 'Start autoplay' );
		toggleButton.textContent = 'Play';
	  } );
	  
	  toggleButton.addEventListener( 'click', function () {
		var Autoplay = splide.Components.Autoplay;
	  
	  
		if ( Autoplay.isPaused() ) {
		  Autoplay.play();
		} else {
		  Autoplay.pause();
		}
	  } ); */
	  

	}(document, window, jQuery));
</script>
<?php

endif;
// close the block
echo $block->after_render();
