<?php
// Redirects

add_action('template_redirect', function () {
    if (is_singular('client')):
        // redirect tot he post type archive
        wp_redirect(get_post_type_archive_link('client'), 301);
        //wp_redirect(home_url(), 301);
        exit;
    endif;

    if (is_singular('team')):
        // redirect tot he post type archive
        $unique_id = sanitize_title(get_the_title(get_the_ID()));
        wp_redirect('/about/our-team/#' . $unique_id, 301);
        //wp_redirect('/about/our-team/', 301);
        exit;
    endif;

    if (is_singular('testimonial')):
        // redirect tot he post type archive
        $unique_id = sanitize_title(get_the_title(get_the_ID()));
        wp_redirect(get_post_type_archive_link('testimonial') . '/#' . $unique_id, 301);
        //wp_redirect('/about/our-team/', 301);
        exit;
    endif;
});
