<?php
$block = $args['block'];

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
);

// Use $loop, a custom variable we made up, so it doesn't overwrite anything
$loop = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
// don't want to use $wp_query, use our custom variable instead.
if ($loop->have_posts()):
?>
	<div class="grid">
		<?php
while ($loop->have_posts()): $loop->the_post();

    $index = $loop->current_post;

    ?>
					<article id="post-<?php the_ID();?>" <?php post_class();?> <?php generate_do_microdata('article');?>>
					<div class="inside-article">

						<div class="post-image">


							<a href="<?php echo get_the_permalink(get_the_ID()); ?>" aria-hidden="true">
								<?php
    the_post_thumbnail('medium');
    ?>
							</a>
						</div>

						<div class="post-content">
							<div <?php generate_do_attr('entry-header');?>>
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

    $arrow = _s_get_icon(
        [
            'icon' => 'link-arrow',
            'group' => 'theme',
            'class' => '',
            'width' => 8,
            'height' => 13,
            'label' => false,
        ]
    );
    ?>

							</div>

							<div class="link-container"><a href="<?php echo get_permalink(); ?>" class="gb-button-link" aria-hidden="true">Read More<span><?php echo $arrow; ?></span></a></div>
						</div>



					</div>
				</article>
				<?php

endwhile;

?>
	</div>
<?php
endif;
wp_reset_postdata();
