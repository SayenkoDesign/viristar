<?php
if (!function_exists('generate_add_secondary_navigation_before_header')) {
    // add_action( 'generate_before_header', 'generate_add_secondary_navigation_before_header', 7 );

    /**
     * Add the navigation before the header.
     *
     * @since 0.1
     */
    function generate_add_secondary_navigation_before_header()
    {
        $generate_settings = wp_parse_args(
            get_option('generate_secondary_nav_settings', array()),
            generate_secondary_nav_get_defaults()
        );

        if ('secondary-nav-above-header' === $generate_settings['secondary_nav_position_setting']) {
            //generate_secondary_navigation_position();
        }

    }
}

// generate_before_header_content
add_action('wp', function () {
    if (!function_exists('generate_secondary_navigation_position')) {
        return;
    }
    remove_action('generate_before_header', 'generate_secondary_navigation_position', 8);
    remove_action('generate_before_header', 'generate_add_secondary_navigation_before_header', 8);
    remove_action('generate_after_primary_navigation', 'generate_secondary_navigation_position', 8);
    add_action('viristar_before_header_content', 'generate_secondary_navigation_position', 0);
});

add_action('add_meta_boxes', function () {
    remove_meta_box('generate_layout_options_meta_box', 'product', 'side');
}, 20);

if (!function_exists('generate_construct_header')) {
    add_action('generate_header', 'generate_construct_header');
    /**
     * Build the header.
     *
     * @since 1.3.42
     */
    function generate_construct_header()
    {
        ?>
		<header <?php generate_do_attr('header');?>>
			<?php do_action('viristar_before_header_content');?>
			<div <?php generate_do_attr('inside-header');?>>
				<?php
/**
         * generate_before_header_content hook.
         *
         * @since 0.1
         */
        do_action('generate_before_header_content');

        if (!generate_is_using_flexbox()) {
            // Add our main header items.
            generate_header_items();
        }

        /**
         * generate_after_header_content hook.
         *
         * @since 0.1
         *
         * @hooked generate_add_navigation_float_right - 5
         */
        do_action('generate_after_header_content');
        ?>
			</div>
		</header>
		<?php
}
}