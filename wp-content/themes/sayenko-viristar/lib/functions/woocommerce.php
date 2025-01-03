<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WooCommerce')) {
    return;
}

function my__template_redirect()
{
    if (is_shop()) {
        wp_redirect(site_url(), '302');
    }
}
add_action('template_redirect', 'my__template_redirect');

function vs_add_to_cart_shortcode()
{
    if (!is_product()) {
        return;
    }
    // Buffer the output
    ob_start();

    $product_id = get_the_ID(); // Assumes you're in the product loop
    $product = wc_get_product($product_id);

    if ($product && $product instanceof WC_Product) {

        $external_product_url = get_field('external_product_url', $product_id);
        $external_product_url_description = get_field('external_product_url_description', $product_id);

        if ($product->is_in_stock()) {
            if (!empty($external_product_url)) {
                $add_to_cart = sprintf('<div class="cart external"><a href="%s" class="gb-button" target="_blank">Enroll Now</a></div>', $external_product_url);
                $add_to_cart .= _s_format_string($external_product_url_description, 'p', ['class' => 'external-product-url-description']);
                echo _s_format_string($add_to_cart, 'div');

            } else {
                do_action('woocommerce_' . $product->get_type() . '_add_to_cart');
            }
        }
    }

    // Get the buffered content and clean the buffer
    $output = ob_get_clean();

    $output = sprintf('<div class="vs-add-to-cart">%s</div>', $output);

    // Return the output
    return $output;
}

// Register the shortcode
add_shortcode('vs_add_to_cart', 'vs_add_to_cart_shortcode');
