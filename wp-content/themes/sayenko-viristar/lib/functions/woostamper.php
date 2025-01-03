<?php

/*
 * Filter the shortcode array to use other values for native shortcodes
 *
 * @param array $shortcodes
 * @return array
 */
function woostamper_filter_shortcodes($shortcodes, $product_id, $order_id, $download_id, $user_email)
{

    $order = wc_get_order($order_id);

    if (is_a($order, 'WC_Order')) {

        // $order = wc_get_order( $order_id );
        $order_data = $order->get_data();

        $company = $order_data['billing']['company'] == '' ? $order_data['shipping']['company'] : $order_data['billing']['company'];

        if (!empty($company)) {
            $shortcodes['[BUSINESSNAME]'] = ", " . $company;
        }

    } else {
        // error_log('Order not found');
    }
    // Format date to YYYY-MM-DD
    if (isset($shortcodes['[DATE]']) && !empty($shortcodes['[DATE]'])) {
        $shortcodes['[DATE]'] = date('Y-m-d', strtotime($shortcodes['[DATE]']));
    }

    return $shortcodes;
}
add_filter('woostamper_filter_shortcodes', 'woostamper_filter_shortcodes', 10, 5);
