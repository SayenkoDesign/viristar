<?php
$display = 3;
$posts_per_page = 15;


$source = get_field('source') ?: 'All';
$source = strtolower($source);

$category = get_field('testimonial_category');

$post_ids = get_field('posts');

$orderby = get_field('orderby') ?: 'Random';
$orderby = strtolower($orderby);

$number_of_testimonials = get_field('number_of_testimonials');

$args = array(
    'post_type' => 'testimonial',
    'order' => 'ASC',
    'posts_per_page' => $posts_per_page,
);

if ('random' === $orderby) {
    // Generate a seed based on the current time
    $seed = time();
    
    // Add randomization to the query arguments
    $args['orderby'] = 'RAND(' . $seed . ')';
    // Remove the 'order' parameter as it's not relevant for random ordering
    unset($args['order']);
}

if('category' === $source && !empty($category)) {
    $category = (array)$category;  // Ensure it's an array
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'testimonial_category',
            'field' => 'term_id',
            'terms' => $category,
        ),
    );
} elseif( 'selected' === $source && !empty($post_ids) ) {
    $posts_per_page = count($post_ids) > 0 ? count($post_ids) : 15;

    $args['post__in'] = $post_ids;
    $args['posts_per_page'] = $posts_per_page;
    $args['orderby'] = 'post__in';
}

$modals = [];

$loop = new WP_Query($args);
$actual_post_count = $loop->post_count;

if ($actual_post_count > 0) :
    
    if (!empty($number_of_testimonials)) {
        $display = $number_of_testimonials;
    }

    $display = min($display, $actual_post_count);


    $type = ($actual_post_count > 2 &&  $display > 2) ? 'loop' : 'slide';
    
    $data_splide = [
        'type' => $type,
        'perPage' => $display,
        'breakpoints' => [
            979 => [
                'perPage' => ($display < 2) ? $display : 2,
                'pagination' => false,
            ],
            767 => [
                'perPage' => 1,
            ]
        ],
    ];

    if ($loop->have_posts()) :
    ?>

<div class="splide-container">
<div id="<?php echo wp_unique_id('splidejs-');?>" class="splide" data-splide="<?php echo esc_attr(json_encode($data_splide)); ?>" aria-label="Testimonials Slider">

      <div class="splide__track">
        <ul class="splide__list">
            <?php
            while ($loop->have_posts()) : $loop->the_post();
            ?>
                <li class="splide__slide">
                    <?php
                    $length = wp_is_mobile() ? 35 : 55;
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
                        'length' => $length
                    ];

                    get_template_part('content', 'testimonial', $args);

                    $arrow = _s_get_icon([
                        'icon'   => 'link-arrow',
                        'group'  => 'theme',
                        'class'  => '',
                        'width'  => 8,
                        'height' => 13,
                        'label'  => false,
                    ]);

                    if ($word_count > $length) {
                        printf('<p><a data-bs-toggle="modal" data-bs-target="#testimonial-modal-%s" aria-hidden="true" class="gb-button-link">%s<span>%s</span></a></p>', 
                            get_the_ID(), __('read more', 'viristar'), $arrow);
                    
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