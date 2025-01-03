<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_filter('generate_featured_image_output', function ($output) {
    if (is_search()) {
        return ''; // Removes the featured image in search results
    }
    return $output;
});

add_filter('generate_search_title_output', function ($output) {
    $output = str_replace('Search Results for:', 'Search:', $output); // Replace with your custom string

    return $output;
});

add_action('generate_before_loop', function () {
    add_action('generate_before_content', function () {
        if (!has_post_thumbnail()) {
            //  echo '<div class="no-image"></div>';
        }
        echo '<div class="post-content">';
    }, 12);

    add_action('generate_after_content', function () {

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
        <?php

    });

    add_action('generate_after_content', function () {
        echo '</div>';
    });
});

add_action('generate_before_loop', function () {
    ?>
	<h5>If you didn't find what you were looking for, try a new search!</h5>
	<?php
get_search_form();
    ?>
    <div class="facetwp-template">
    <?php
});

add_action('generate_after_loop', function () {
    ?></div><?php
}, 1);

add_filter('generate_svg_icon', function ($output, $icon) {
    if ('categories' === $icon) {
        return false;
    }

    return $output;
}, 15, 2);

add_filter('generate_term_separator', function () {
    return '';
});

add_filter('generate_next_link_text', function () {
    $next = _s_get_icon(
        [
            'icon' => 'next',
            'group' => 'theme',
            'class' => 'next-icon',
            'width' => 94,
            'height' => 94,
            'label' => false,
        ]
    );
    return sprintf('<span class="screen-reader-text" title="%1$s">%1$s</span>%2$s', esc_attr__('Next', 'generatepress'), $next);
});

add_filter('generate_previous_link_text', function () {
    $previous = _s_get_icon(
        [
            'icon' => 'previous',
            'group' => 'theme',
            'class' => 'previous-icon',
            'width' => 94,
            'height' => 94,
            'label' => false,
        ]
    );
    return sprintf('<span class="screen-reader-text" title="%1$s">%1$s</span>%2$s', esc_attr__('Previous', 'generatepress'), $previous);
});

get_header();?>

	<div <?php generate_do_attr('content');?>>
		<main <?php generate_do_attr('main');?>>
			<?php
/**
 * generate_before_main_content hook.
 *
 * @since 0.1
 */
do_action('generate_before_main_content');

if (generate_has_default_loop()) {
    if (have_posts()):
        /**
         * generate_before_loop hook.
         *
         * @since 3.1.0
         */

        do_action('generate_before_loop', 'search');

        while (have_posts()):

            the_post();

            get_template_part('template-parts/search-results', get_post_type());

            //generate_do_template_part('search');

        endwhile;

        /**
         * generate_after_loop hook.
         *
         * @since 2.3
         */
        do_action('generate_after_loop', 'search');

    else:

        generate_do_template_part('none');

    endif;
}

/**
 * generate_after_main_content hook.
 *
 * @since 0.1
 */
do_action('generate_after_main_content');
?>
		</main>
	</div>

	<?php
/**
 * generate_after_primary_content_area hook.
 *
 * @since 2.0
 */
do_action('generate_after_primary_content_area');

generate_construct_sidebars();

get_footer();
