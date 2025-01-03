<?php
/*
 * Hooks for WooCommerce
 */

if (!class_exists('WooCommerce')) {
    return;
}

add_action('body_class', function ($classes) {
    if (is_product() && !empty(get_field('course_type'))) {
        $classes[] = 'single-product--has-course-type';
    }
    return $classes;
});

// Change the add to cart button text on single product pages
function custom_single_add_to_cart_text($text)
{

    global $post;

    $course_type = get_field('course_type', $post->ID);

    if (!empty($course_type)) {
        return __('Enroll Now', 'woocommerce'); // Change 'Custom Text' to your desired text
    }

    return $text;

}
add_filter('woocommerce_product_single_add_to_cart_text', 'custom_single_add_to_cart_text');

// Disable new WooCommerce product template (from Version 7.7.0)
function restored_reset_product_template($post_type_args)
{

    if (array_key_exists('template', $post_type_args)) {
        unset($post_type_args['template']);
    }
    return $post_type_args;
}
add_filter('woocommerce_register_post_type_product', 'restored_reset_product_template');

// Enable Gutenberg editor for WooCommerce
function restored_activate_gutenberg_product($can_edit, $post_type)
{

    if ($post_type == 'product') {
        $can_edit = true;
    }
    return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'restored_activate_gutenberg_product', 10, 2);

// Enable taxonomy fields for woocommerce with gutenberg on
function restored_enable_taxonomy_rest($args)
{
    $args['show_in_rest'] = true;
    return $args;
}
add_filter('woocommerce_taxonomy_args_product_cat', 'restored_enable_taxonomy_rest');
add_filter('woocommerce_taxonomy_args_product_tag', 'restored_enable_taxonomy_rest');

add_action('wp', function () {

    if (!is_product()) {
        return;
    }

    global $post;

    // Remove breadcrumbs
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

    // Remove related products
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

    $course = new \App\Course($post->ID);

    $course_category = $course->get_category('slug');

    if (!$course_category) {
        return;
    }

    function custom_currency_symbol($currency_symbol, $currency)
    {
        if ($currency == 'USD') {
            return 'USD ';
        }
        return $currency_symbol;
    }
    //add_filter('woocommerce_currency_symbol', 'custom_currency_symbol', 10, 2);

    add_action('woocommerce_after_single_product', function () {
        printf('%s', do_shortcode('[add_to_cart id]'));
    });

});

add_filter('woocommerce_get_price_html', function ($price, $product) {

    $product_id = $product->get_id();
    $course_object = new \App\Course($product_id);
    if (!empty($course_object) && !is_wp_error($course_object)) {
        if ($course_object->get_title()) {
            // Get the WooCommerce currency code (e.g., "USD", "EUR")
            $currency_code = get_woocommerce_currency();

            // Define the currency format with the code before the price
            $price = $currency_code . ' ' . $product->get_price();
        }
    }

    return $price;

}, 10, 2);
