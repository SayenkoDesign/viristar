<?php
$selected = get_field('selected'); // none, posts, categories

if('None' === $selected || empty($selected)) {
	return false;
}

$selected = strtolower($selected);

$posts = get_field('posts');

$categories = get_field('categories');

$args = [
	'post_status' 		  => 'publish',
	'posts_per_page'      => 3,
	'post__not_in'   	  => array( get_the_ID() )
];

if('posts' === $selected) {
	$args['post__in'] = $posts;

	if(empty($posts)) {
		return false;
	}
}

if('categories' === $selected) {
	$args['category__in'] = $categories;

	if(empty($categories)) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$category_id = $categories[0]->cat_ID;
			$args['category__in'] = [$category_id];
		}
	}

	
}


$loop = new WP_Query($args);

if (4 > $loop->found_posts) {
	// return false;
}

if ($loop->have_posts()) :

	echo '<div class="related-posts">';

	printf('<h2 class="h3">%s</h2>', __('Related Posts', 'viristar'));

	echo '<div class="grid">';

	while ($loop->have_posts()) : $loop->the_post();

?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_do_microdata('article'); ?>>
			<div class="inside-article">

				<div class="post-image">
					

					<a href="<?php echo get_the_permalink(get_the_ID()); ?>" aria-hidden="true">
						<?php
						the_post_thumbnail('medium');
						?>
					</a>
				</div>

				<header <?php generate_do_attr('entry-header'); ?>>
					<?php

					$params = array(
						'before' => sprintf(
							'<h3 class="entry-title"%2$s><a href="%1$s" rel="bookmark">',
							esc_url(get_permalink()),
							'microdata' === generate_get_schema_type() ? ' itemprop="headline"' : ''
						),
						'after' => '</a></h3>',
					);

					the_title($params['before'], $params['after']);

					print( '<div class="entry-meta">');
					generate_do_post_meta_item('date');
					print( '</div>');

					?>
				</header>

			</div>
		</article>
<?php

	endwhile;

	echo '</div></div>';
endif;

wp_reset_postdata();