<?php
$args = [
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'post__not_in' => array(get_the_ID()),
];

$loop = new WP_Query($args);

if ($loop->have_posts()):

    echo '<div class="related-posts">';

    printf('<h2 class="h3">%s</h2>', __('Recent Posts', 'viristar'));

    echo '<div class="grid">';

    while ($loop->have_posts()): $loop->the_post();

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

						            <header <?php generate_do_attr('entry-header');?>>
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

        // print('<div class="entry-meta">');
        //generate_do_post_meta_item('date');
        // print('</div>');

        ?>
																												</header>
																        <?php
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
																		<div class="link-container"><a href="<?php echo get_permalink(); ?>" class="gb-button-link" aria-hidden="true">Read More<span><?php echo $arrow; ?></span></a></div>

																				    </div>
																											</div>
																										</article>
																								<?php

    endwhile;

    echo '</div></div>';
endif;

wp_reset_postdata();