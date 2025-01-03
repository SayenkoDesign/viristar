<?php

$post_ids = get_field('posts');

$posts_per_page = 15;

$source = get_field('source') ?: 'All';
$source = strtolower($source);

$category = get_field('category');

$post_ids = get_field('posts') ?: [];

$orderby = get_field('orderby') ?: 'Random';
$orderby = strtolower($orderby);

// Generate a seed based on the current time
$seed = time();

$args = array(
    'post_type' => 'client',
    'order' => 'ASC',
    'orderby' => 'title',
    'ignore_custom_sort' => true,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'posts_per_page' => $posts_per_page,
);

if ('select' === $source && !empty($post_ids)) {
    // $posts_per_page = count($post_ids) > 0 ? count($post_ids) : 15;

    $args['post__in'] = $post_ids;
    $args['posts_per_page'] = $posts_per_page;
    $args['orderby'] = 'post__in';

} else if ('category' === $source && !empty($category)) {
    $category = (array) $category; // Ensure it's an array
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'client_category',
            'field' => 'term_id',
            'terms' => $category,
        ),
    );

    // Add this line to explicitly enforce the posts_per_page limit
}

if (count($post_ids) > $posts_per_page || 'category' === $source || 'random' === $orderby) {

    // Add randomization to the query arguments
    //$args['orderby'] = 'RAND(' . $seed . ')';
    $args['orderby'] = 'rand';
    // Remove the 'order' parameter as it's not relevant for random ordering
    // unset($args['order']);
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
        ],
    ],
];

// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()):

?>

<div id="<?php echo wp_unique_id('splidejs-'); ?>" class="splide" data-splide="<?php echo esc_attr(json_encode($data_splide)); ?>" aria-label="Clients Slider">
  <div class="splide__track">
    <ul class="splide__list">
		<?php
while ($loop->have_posts()): $loop->the_post();
    ?>
																							<li class="splide__slide">
																								<div class="logo">
																								<?php
    the_post_thumbnail('medium');
    ?>
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