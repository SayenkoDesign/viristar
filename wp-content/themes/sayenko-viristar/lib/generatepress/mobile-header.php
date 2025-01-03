<?php

remove_action('generate_after_header', 'generate_menu_plus_mobile_header', 5);
add_action('generate_after_header', 'tic_generate_menu_plus_mobile_header', 5);

remove_action('generate_inside_mobile_header', 'generate_navigation_search', 1);
remove_action('generate_inside_mobile_header', 'generate_mobile_menu_search_icon');

/**
 * Build the mobile header.
 */
function tic_generate_menu_plus_mobile_header()
{
    $settings = wp_parse_args(
        get_option('generate_menu_plus_settings', array()),
        generate_menu_plus_get_defaults()
    );

    if ('disable' === $settings['mobile_header']) {
        return;
    }

    $attributes = array(
        'id' => 'mobile-header',
    );

    if ('false' !== $settings['mobile_header_auto_hide_sticky'] && $settings['mobile_header_auto_hide_sticky']) {
        $hide_sticky = ' data-auto-hide-sticky="true"';
        $attributes['data-auto-hide-sticky'] = true;
    } else {
        $hide_sticky = '';
    }

    $microdata = 'itemtype="https://schema.org/SiteNavigationElement" itemscope';

    if (function_exists('generate_get_microdata')) {
        $microdata = generate_get_microdata('navigation');
    }

    if (function_exists('generate_get_schema_type')) {
        if ('microdata' === generate_get_schema_type()) {
            $attributes['itemtype'] = 'https://schema.org/SiteNavigationElement';
            $attributes['itemscope'] = true;
        }
    }

    $classes = array(
        'main-navigation',
        'mobile-header-navigation',
    );

    if (('logo' === $settings['mobile_header_branding'] && '' !== $settings['mobile_header_logo']) || 'title' === $settings['mobile_header_branding']) {
        $classes[] = 'has-branding';
    }

    if ('enable' === $settings['mobile_header_sticky']) {
        if (('logo' === $settings['mobile_header_branding'] && '' !== $settings['mobile_header_logo']) || 'title' === $settings['mobile_header_branding']) {
            $classes[] = 'has-sticky-branding';
        }
    }

    if (function_exists('generatepress_wc_get_setting') && generatepress_wc_get_setting('cart_menu_item')) {
        $classes[] = 'wc-menu-cart-activated';
    }

    if (function_exists('generate_is_using_flexbox') && generate_is_using_flexbox()) {
        if (function_exists('generate_has_menu_bar_items') && generate_has_menu_bar_items()) {
            $classes[] = 'has-menu-bar-items';
        }
    }

    $classes = implode(' ', $classes);
    $attributes['class'] = $classes;

    $mobile_header_attributes = sprintf(
        'id="mobile-header"%1$s class="%2$s" %3$s"',
        $hide_sticky,
        esc_attr($classes),
        $microdata
    );

    if (function_exists('generate_get_attr')) {
        $mobile_header_attributes = generate_get_attr(
            'mobile-header',
            $attributes
        );
    }
    ?>
		<nav <?php echo $mobile_header_attributes; // phpcs:ignore -- No escaping needed.                         ?>>
			<div class="inside-navigation grid-container grid-parent">
				<?php
do_action('generate_inside_mobile_header');

    $disable_mobile_header_menu = apply_filters('generate_disable_mobile_header_menu', false);

    if (!$disable_mobile_header_menu):
        if (function_exists('generate_get_attr')) {
            $menu_toggle_attributes = generate_get_attr(
                'mobile-header-menu-toggle',
                array(
                    'class' => 'menu-toggle',
                    'aria-controls' => 'mobile-menu',
                    'aria-expanded' => 'false',
                )
            );
        } else {
            $menu_toggle_attributes = 'class="menu-toggle" aria-controls="mobile-menu" aria-expanded="false"';
        }
        ?>
																													<button <?php echo $menu_toggle_attributes; // phpcs:ignore -- No escaping needed.                         ?>>
																														<?php
    do_action('generate_inside_mobile_header_menu');

        if (function_exists('generate_do_svg_icon')) {
            generate_do_svg_icon('menu-bars', true);
        }

        $mobile_menu_label = apply_filters('generate_mobile_menu_label', __('Menu', 'gp-premium'));

        if ($mobile_menu_label) {
            printf(
                '<span class="mobile-menu">%s</span>',
                $mobile_menu_label // phpcs:ignore -- HTML allowed.
            );
        } else {
            printf(
                '<span class="screen-reader-text">%s</span>',
                __('Menu', 'gp-premium')
            );
        }
        ?>
																													</button>
																													<?php
    /**
         * generate_after_mobile_header_menu_button hook
         *
         * @since 1.11.0
         */
        do_action('generate_after_mobile_header_menu_button');
    endif;

    echo '<div id="mobile-menu-items">';

    printf('<div class="mobile-menu-search-form">%s</div>', get_search_form(false));

    wp_nav_menu(
        array(
            'theme_location' => apply_filters('generate_mobile_header_theme_location', 'primary'),
            'container' => 'div',
            'container_class' => 'main-nav',
            'container_id' => 'mobile-menu',
            'menu_class' => '',
            'fallback_cb' => 'generate_menu_fallback',
            'items_wrap' => '<ul id="%1$s" class="%2$s ' . join(' ', generate_get_menu_class()) . '">%3$s</ul>',
        )
    );

    do_action('generate_mobile_after_primary_menu');

    echo '</div>';

    /**
     * generate_after_primary_menu hook.
     *
     * @since 1.11.0
     */
    do_action('generate_after_primary_menu');
    ?>
			</div><!-- .inside-navigation -->
		</nav><!-- #site-navigation -->
		<?php
}