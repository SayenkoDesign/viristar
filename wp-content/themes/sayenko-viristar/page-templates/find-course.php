<?php
/*
Template Name: Find a Course
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_filter('body_class', function ($classes) {
    unset($classes[array_search('page-template-default', $classes)]);
    $classes[] = 'contact';
    return $classes;
}, 99);

add_action('generate_before_main_content', function () {
    ?>
	<button class="filter-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#left-sidebar" aria-controls="offcanvasLeftsidebar">
  Filters
</button>
	<?php
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
    while (have_posts()):

        the_post();

        global $post;

        generate_do_template_part('page');

    endwhile;
}

/**
 * generate_after_main_content hook.
 *
 * @since 0.1
 */
do_action('generate_after_main_content');

// Course Grid

use \App\Course_Finder;

$course_finder = new Course_Finder();

$course_finder->render();
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
