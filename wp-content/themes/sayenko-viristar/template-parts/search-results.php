<?php

/**
 * The template for displaying posts within the loop.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<article id="post-<?php the_ID();?>" <?php post_class();?> <?php generate_do_microdata('article');?>>
	<div class="inside-article">
		<header>
			<?php
$post_type = get_post_type();

if ('product' == get_post_type()) {
    $course_type = get_field('course_type');
    if (!empty($course_type)) {
        $post_type = 'Course';
    }
}

if ('course' == get_post_type()) {
    $post_type = 'Course Type';
}

if ('post' == get_post_type()) {
    $post_type = 'Article';
}

if ('tribe_events' == get_post_type()) {
    $post_type = 'Event';
}

$permalink = get_permalink();

if ('team' == get_post_type()) {
    $permalink = '/about/our-team/#' . sanitize_title(get_the_title());
}

if ('testimonial' == get_post_type()) {
    $permalink = get_post_type_archive_link('testimonial') . '?testimonial_id=' . get_the_ID() . '#content';
}

printf('<div><span class="post-type-label">%s</span></div>', $post_type);

$title = get_the_title();

if ('product' == get_post_type()) {
    $course_type = get_field('course_type');
    if (!empty($course_type)) {
        $course = get_field('course');

        $title = get_the_title($course);
    }
}

printf('<h3 class="entry-title" itemprop="headline"><a href="%s">%s</a></h3>', $permalink, $title);

if ('product' == get_post_type()) {

}

if ('post' == get_post_type()) {
    ob_start();
    generate_do_post_meta_item('date');
    $post_meta = ob_get_clean();
    printf('<div class="entry-meta"><strong>Posted:</strong> %s</div>', $post_meta);
}

if ('product' == get_post_type()) {

    $start_date = get_field('start_date', $product_id);
    $end_date = get_field('end_date', $product_id);

    if ($start_date && $end_date) {
        $formatted_start = date('F j', strtotime($start_date));
        $formatted_end = date('F j', strtotime($end_date));

        printf('<div class="entry-meta"><strong>Dates:</strong> %s - %s</div>', $formatted_start, $formatted_end);
    }

}

// Get the current event
if (function_exists('tribe_get_event')) {
    $event = tribe_get_event(get_the_ID());

// Check if it's an event
    if (get_post_type() === 'tribe_events') {
        echo '<div class="event-meta">';

        // Output event date
        echo '<p><strong>Date:</strong> ' . tribe_get_start_date($event, false, 'F j, Y') . '</p>';

        // Output event venue
        if (tribe_get_venue()) {
            echo '<p><strong>Venue:</strong> ' . tribe_get_venue() . '</p>';
        }

        // Output event organizer
        if (tribe_get_organizer()) {
            echo '<p><strong>Organizer:</strong> ' . tribe_get_organizer() . '</p>';
        }

        echo '</div>';
    }
}

//the_excerpt();

?>
		</header>
	</div>
</article>