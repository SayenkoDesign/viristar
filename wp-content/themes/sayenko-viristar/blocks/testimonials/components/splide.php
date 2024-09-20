<?php

$number_of_testimonials = get_field('number_of_testimonials');

$post_ids = get_field('posts');
$posts_per_page = !empty($post_ids) ? count($post_ids) : 15;

$args = array(
    'post_type' => 'testimonial',
    'order' => 'ASC',
    'orderby' => 'rand',
    'posts_per_page' => $posts_per_page,
);

if (!empty($post_ids)) {
    $args['orderby'] = 'post__in';
    $args['post__in'] = $post_ids;
}

$modals = [];

$loop = new WP_Query($args);
$actual_post_count = $loop->post_count;

if ($actual_post_count > 0) :
    $number_of_testimonials = min($number_of_testimonials ?: 3, $actual_post_count);

    if ($loop->have_posts()) :
    ?>

    <div class="splide" aria-label="Success Stories Slider">
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
    <?php
    echo join('', $modals);
    ?>

    <script>
    (function (document, window, $) {
        const splideElements = document.querySelectorAll('.splide');
        const splideInstances = new Map();

        splideElements.forEach(element => {
            const splideInstance = initializeSplide(element);
            splideInstances.set(element, splideInstance);
        });

        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabButton => {
            tabButton.addEventListener('shown.bs.tab', function (event) {
                const tabContent = document.querySelector(event.target.getAttribute('data-bs-target'));
                const tabSplides = tabContent.querySelectorAll('.splide');

                tabSplides.forEach(splideElement => {
                    const splideInstance = splideInstances.get(splideElement);
                    if (splideInstance) {
                        splideInstance.refresh();
                    }
                });
            });
        });

        function initializeSplide(element) {
            const actualPostCount = <?php echo $actual_post_count; ?>;
            const numberOfTestimonials = <?php echo $number_of_testimonials; ?>;

            const splide = new Splide(element, {
                type: actualPostCount > numberOfTestimonials ? "loop" : "slide",
                autoplay: false,
                rewind: false,
                speed: 500,
                interval: 3000,
                pauseOnHover: true,
                pauseOnFocus: true,
                pagination: actualPostCount > 1,
                arrows: actualPostCount > numberOfTestimonials,
                perPage: numberOfTestimonials,
                perMove: numberOfTestimonials,
                breakpoints: {
                    1500: {
                        // arrows: false,
                    },
                    1280: {
                        perMove: Math.min(2, actualPostCount),
                        perPage: Math.min(2, actualPostCount),
                        pagination: false,
                    },
                    979: {
                        perMove: 1,
                        perPage: 1
                    },
                },
            });

            splide.mount();
            return splide;
        }

    }(document, window, jQuery));
    </script>
    <?php
    endif; // end if ($loop->have_posts())
endif; // end if ($actual_post_count > 0)

wp_reset_postdata();