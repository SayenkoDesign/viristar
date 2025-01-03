<?php
/**
 * The template for displaying Archive pages.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function get_testimonial_details()
{
    $length = wp_is_mobile() ? 18 : 25;
    $content = get_the_content();
    $word_count = str_word_count(trim(strip_tags($content)));

    $teaser = wpautop(wp_trim_words($content, $length, '...'));
    $full = apply_filters('the_content', $content);

    if ($word_count <= $length) {
        $teaser = $full;
        $full = '';
    }

    return [
        'teaser' => $teaser,
        'full' => $full,
        'image' => _s_format_string(get_the_post_thumbnail(get_the_ID(), 'thumbnail'), 'figure', ['class' => 'testimonial__image']),
        'logo' => get_field('logo'),
        'details' => _s_format_string(join(', ', array_filter([
            get_field('job_title'),
            get_field('organization'),
            get_field('location'),
        ])), 'p'),
        'word_count' => $word_count,
        'length' => $length,
    ];
}

function format_testimonial_html()
{
    ob_start();
    get_template_part('content', 'testimonial', get_testimonial_details());
    get_template_part('template-parts/testimonial', 'modal', get_testimonial_details());
    return ob_get_clean();
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

        global $wp_query;

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

        $_posts = [];

        while (have_posts()):

            the_post();
            $_posts[] = format_testimonial_html(get_testimonial_details(get_the_ID()));

        endwhile;

        mt_srand(time()); // Add this line to get different results each time
        shuffle($_posts);

        // Add excluded testimonial if needed
        // Add excluded testimonial if needed
        if (isset($_GET['testimonial_id'])) {
            $testimonial_id = filter_var($_GET['testimonial_id'], FILTER_VALIDATE_INT);
            if ($testimonial_id > 0) {
                $excluded_post = get_post($testimonial_id);
                if ($excluded_post && $excluded_post->post_type === 'testimonial') {
                    global $post;
                    $post = $excluded_post;
                    setup_postdata($post);
                    array_unshift($_posts, format_testimonial_html($testimonial_id));
                }
            }
        }

        echo join('', $_posts);

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
