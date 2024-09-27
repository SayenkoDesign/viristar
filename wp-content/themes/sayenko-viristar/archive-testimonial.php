<?php
/**
 * The template for displaying Archive pages.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

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
         * generate_archive_title hook.
         *
         * @since 0.1
         *
         * @hooked generate_archive_title - 10
         */
        do_action('generate_archive_title');

        /**
         * generate_before_loop hook.
         *
         * @since 3.1.0
         */
        do_action('generate_before_loop', 'archive');

        while (have_posts()):

            the_post();

            $length = 55;

            $word_count = str_word_count(trim(strip_tags(get_the_content(get_the_ID()))));

            $teaser = wpautop(wp_trim_words(get_the_content(get_the_ID()), $length, '...'));

            $image = _s_format_string(get_the_post_thumbnail(get_the_ID(), 'thumbnail'), 'figure', ['class' => 'testimonial__image']);

            $details = [];

            $details[] = get_field('job_title', get_the_ID());
            $details[] = get_field('organization', get_the_ID());
            $details[] = get_field('location', get_the_ID());

            $details = array_filter($details);

            $details = _s_format_string(join(', ', $details), 'p');

            $full = apply_filters('the_content', get_the_content(get_the_ID()));

            $logo = get_field('logo', get_the_ID());

            $args = [
                'teaser' => $teaser,
                'full' => $full,
                'image' => $image,
                'logo' => $logo,
                'details' => $details,
                'word_count' => $word_count,
                'length' => $length,

            ];

            get_template_part('content', 'testimonial', $args);

        endwhile;

        /**
         * generate_after_loop hook.
         *
         * @since 2.3
         */
        do_action('generate_after_loop', 'archive');

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
