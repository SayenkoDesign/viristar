<?php

$post_ids = get_field('posts');

$args = array(
	'post_type' => 'testimonial',
	'order' => 'ASC',
	'orderby' => 'title',
	'posts_per_page' => 5,
);

if (!empty($post_ids)) {
	$args['orderby'] = 'post__in';
	$args['post__in'] = $post_ids;
	$args['posts_per_page'] = count($post_ids);
}




// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()) :

?>


<div class="splide" aria-label="success Stories Slider">
  <div class="splide__track">
    <ul class="splide__list">
		<?php
		while ($loop->have_posts()) : $loop->the_post();

		$logo = get_field('logo', get_the_ID());
		$logo = wp_get_attachment_image($logo, 'medium', false, array('class' => 'testimonial__logo'));

		$designation =  _s_format_string( get_field('designation', get_the_ID()), 'span', array( 'class' => 'testimonial__designation' ) );
		
		$sub_heading = _s_format_string( get_field('sub_heading', get_the_ID()), 'span', array( 'class' => 'testimonial__sub_heading' ) );
		$separator = ! empty( $designation ) && ! empty( $sub_heading ) ? ', ' : '';
		$name    =  _s_format_string( get_the_title(get_the_ID()), 'div', array( 'class' => 'testimonial__name' ) );
		?>
			<li class="splide__slide">
				<?php
				echo $logo;
				?>
				<div class="testimonial">
				<?php
				echo _s_format_string( get_the_post_thumbnail(get_the_ID(), 'medium'), 'div', array( 'class' => 'testimonial__image' ) );
				?>

				<div class="testimonial__quote">
				<svg xmlns="http://www.w3.org/2000/svg" width="34" height="22" viewBox="0 0 34 22" fill="none">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M32.2376 18.5745C30.4752 18.2851 26.9001 17.3684 25.742 14.6666C31.2305 14.6666 34 11.3859 34 7.33333C34 3.2807 30.5759 0 26.3462 0C22.1165 0 18.6924 3.03947 18.6924 8.68421C18.6924 16.5 24.7852 21.9035 31.3816 22C32.4894 22 33.0936 21.3728 33.0936 20.3114V20.2631C33.0936 19.2017 32.7915 18.7193 32.2376 18.5745ZM7.6538 4.13447e-05C11.8835 4.13447e-05 15.3076 3.28074 15.3076 7.33337C15.3076 11.3378 12.4878 14.6667 7.04955 14.6667C8.2077 17.3685 11.7828 18.2852 13.5452 18.5746C14.0991 18.7194 14.4012 19.2018 14.4012 20.2632V20.3115C14.4012 21.3729 13.797 22.0001 12.6892 22.0001C6.09283 21.9036 0 16.5001 0 8.68425C0 3.03951 3.42407 4.13447e-05 7.6538 4.13447e-05Z" fill="#87A7CE"/>
				</svg>
				<?php 
				echo _s_format_string( get_field('content', get_the_ID()), 'div', array( 'class' => 'testimonial__content' ) );
			
				echo _s_format_string( $name . $designation . $separator . $sub_heading , 'div', array( 'class' => 'testimonial__cite' ) );

				?>
				</div>
				</div>
			</li>
		<?php
		endwhile;
		?>
	  </ul>
  </div>
   <div class="autoplay-controls" style="display: none;">
		<button class="my-toggle-button" type="button">Pause</button>
  </div>
</div>
<?php
endif;
wp_reset_postdata();