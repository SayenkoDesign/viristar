<?php
/*
Header customization
 */

add_filter('body_class', function ($classes) {
    // Sticky Header
    $sticky_header = get_field('sticky_header');
    if (!empty($sticky_header)) {
        $sticky_header = strtolower($sticky_header);

        if ('yes' == $sticky_header) {
            $classes[] = 'sticky-header';
        }
    }

    return $classes;
}, 99);

add_action('wp_enqueue_scripts', 'generate_dequeue_secondary_nav_mobile', 999);
function generate_dequeue_secondary_nav_mobile()
{
    wp_dequeue_style('generate-secondary-nav-mobile');
}

/* add_action('generate_before_header', function() {
echo '<div class="site-header-wrapper">';
}, 1);

add_action('generate_after_header', function() {
echo '</div>';
});
 */

add_action('wp', function () {
    if (generate_get_option('nav_search_modal')) {
        remove_action('generate_menu_bar_items', 'generate_do_search_modal_trigger');
        add_action('generate_inside_secondary_navigation', 'generate_do_search_modal_trigger');
    }
}, 20);

add_filter('wp_nav_menu_items', function ($nav, $args) {
    // If our primary menu is set, add the search icon.
    if ('secondary' === $args->theme_location && generatepress_wc_get_setting('cart_menu_item')) {

        if (!class_exists('WooCommerce')) {
            return;
        }

        if (!isset(WC()->cart)) {
            return;
        }

        $has_items = false;

        if (!WC()->cart->is_empty()) {
            $has_items = 'has-items';
        }

        return sprintf(
            '%1$s
				<li class="wc-menu-item menu-item-align-right %3$s %4$s">
					%2$s
				</li>',
            $nav,
            generatepress_wc_cart_link(),
            is_cart() ? 'current-menu-item' : '',
            $has_items
        );
    }

    // Our primary menu isn't set, return the regular nav.
    return $nav;
}, 10, 2);
