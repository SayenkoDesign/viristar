<?php

if (is_product()) {

    $product_id = get_the_ID(); // Assumes you're in the product loop
    $product = wc_get_product($product_id);

    if ($product && $product instanceof WC_Product) {

        $external_product_url = get_field('external_product_url', $product_id);
        $external_product_url_description = get_field('external_product_url_description', $product_id);

        if ($product->is_in_stock()) {
            if (!empty($external_product_url)) {
                $add_to_cart = sprintf('<div class="cart external"><a href="%s" class="gb-button" target="_blank">Enroll Now</a></div>', $external_product_url);
                // $add_to_cart .= _s_format_string( $external_product_url_description, 'p', ['class' => 'external-product-url-description'] );
                echo _s_format_string($add_to_cart, 'div');

            } else {
                do_action('woocommerce_' . $product->get_type() . '_add_to_cart');
                //woocommerce_template_single_add_to_cart();
            }
        }
    }

}
