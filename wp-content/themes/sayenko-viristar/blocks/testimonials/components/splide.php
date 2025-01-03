<?php
$classes = [];

$display = 3;
$posts_per_page = 15;

$source = get_field('source') ?: 'All';
$source = strtolower($source);

$category = get_field('category');

$post_ids = get_field('posts') ?: [];

$orderby = get_field('orderby') ?: 'Random';
$orderby = strtolower($orderby);

$number_of_testimonials = get_field('number_of_testimonials');
$posts_per_page = $number_of_testimonials ?: 15; // Set a default value

$show_read_more = get_field('show_read_more') ?: 'Yes';
$show_read_more = strtolower($show_read_more);
$show_read_more = 'yes' === $show_read_more ? true : false;

$hide_logos = get_field('hide_logos') ?: 'No';
$hide_logos = strtolower($hide_logos);
$classes[] = sprintf('splide--hide-logos-%s', $hide_logos);

$seed = time();

$args = array(
    'post_type' => 'testimonial',
    'order' => 'ASC',
    'posts_per_page' => $posts_per_page,
    'ignore_custom_sort' => true,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
);

if (count($post_ids) > $posts_per_page || 'category' === $source || 'random' === $orderby) {

    // Add randomization to the query arguments
    $args['orderby'] = 'rand ID';

    // Remove the 'order' parameter as it's not relevant for random ordering
    //unset($args['order']);
}

if ('category' === $source && !empty($category)) {
    $category = (array) $category; // Ensure it's an array
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'testimonial_category',
            'field' => 'term_id',
            'terms' => $category,
        ),
    );
} elseif ('selected' === $source && !empty($post_ids)) {
    $posts_per_page = count($post_ids) > 0 ? count($post_ids) : 15;

    $args['post__in'] = $post_ids;
    $args['posts_per_page'] = $posts_per_page;
    $args['orderby'] = 'post__in';
} else {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'testimonial_category',
            'field' => 'slug',
            'terms' => 'exclude',
            'operator' => 'NOT IN',
        ),
    );
}

$modals = [];

$loop = new WP_Query($args);
$actual_post_count = $loop->post_count;

if ($actual_post_count > 0):

    if (1 === $actual_post_count) {
        //  $classes = ['testimonial-single'];
    }

    if (!empty($number_of_testimonials)) {
        $display = $number_of_testimonials;
    }

    $display = min($display, $actual_post_count);

    $type = ($actual_post_count > 2 && $display > 2) ? 'loop' : 'slide';

    $data_splide = [
        'type' => $type,
        'perPage' => $display,
        'breakpoints' => [
            1279 => [
                'perPage' => ($display < 2) ? $display : 2,
            ],
            1023 => [
                'perPage' => 1,
                'pagination' => false,
            ],
        ],
    ];

    $arrows = get_field('arrows') ?: 'Show';
    $arrows = strtolower($arrows);

    if ('hide' === $arrows) {
        $data_splide['arrows'] = false;
    }

    if ($loop->have_posts()):
    ?>

					        <div class="splide-container">
					            <div id="<?php echo wp_unique_id('splidejs-'); ?>" class="splide <?php echo join(' ', $classes); ?>" data-splide="<?php echo esc_attr(json_encode($data_splide)); ?>" aria-label="Testimonials Slider">

					                <div class="splide__track">
					                    <ul class="splide__list">
					                        <?php
    while ($loop->have_posts()): $loop->the_post();
        ?>
										                            <?php
        $length = 25;

        if (wp_is_mobile()) {
            $length = 18;
        }

        $word_count = str_word_count(trim(strip_tags(get_the_content(get_the_ID()))));

        $teaser = wpautop(wp_trim_words(get_the_content(get_the_ID()), $length, '...'));

        $full = apply_filters('the_content', get_the_content(get_the_ID()));

        if ($word_count <= $length || !$show_read_more) {
            $teaser = $full;
            $full = '';
        }

        $image = _s_format_string(get_the_post_thumbnail(get_the_ID(), 'thumbnail'), 'figure', ['class' => 'testimonial__image']);

        $details = [];
        $details[] = get_field('job_title', get_the_ID());
        $details[] = get_field('organization', get_the_ID());
        $details[] = get_field('location', get_the_ID());
        $details = array_filter($details);
        $details = _s_format_string(join(', ', $details), 'p');

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
        ?>
										                            <li class="splide__slide">
										                                <?php

        get_template_part('content', 'testimonial', $args);

        if ($word_count > $length && $show_read_more) {

            ob_start();
            get_template_part('template-parts/testimonial', 'modal', $args);
            $modals[] = ob_get_clean();
        }
        ?>
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
					        </div>
					<?php
    echo join('', $modals);
endif; // end if ($loop->have_posts())
endif; // end if ($actual_post_count > 0)

wp_reset_postdata();
