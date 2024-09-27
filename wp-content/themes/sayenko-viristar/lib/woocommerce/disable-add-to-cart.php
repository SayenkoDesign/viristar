<?php

function viristar_should_disable_add_to_cart($product_id) {
    $start_date = get_field('start_date', $product_id);
    $timezone = get_field('timezone', $product_id);
    $disable_buy_now = get_field('disable_buy_now', $product_id);

    if (empty($start_date) || empty($timezone) || !is_array($disable_buy_now)) {
        error_log("Missing required fields for product ID: " . $product_id);
        return false;
    }

    try {
        $timezone_object = new DateTimeZone($timezone);
        $event_date = DateTime::createFromFormat('Y-m-d H:i:s', $start_date, $timezone_object);
        $now = new DateTime('now', $timezone_object);

        if (!$event_date) {
            throw new Exception("Invalid date format for start_date: " . $start_date);
        }

        // Check if the event has already passed
        if ($now > $event_date) {
            error_log("Event has passed for product ID: " . $product_id);
            return true;
        }

        if (strtolower($disable_buy_now['when']) !== 'never') {
            $amount = intval($disable_buy_now['time']);
            $unit = strtolower($disable_buy_now['units']);
            $when = strtolower($disable_buy_now['when']);

            if ($unit === 'days') {
                $interval_string = "P{$amount}D";
            } elseif ($unit === 'hours') {
                $interval_string = "PT{$amount}H";
            } else {
                throw new Exception("Invalid time unit: " . $unit);
            }

            $interval = new DateInterval($interval_string);
            $disable_time = clone $event_date;
            
            if ($when === 'before') {
                $disable_time->sub($interval);
                $result = $now >= $disable_time;
            } elseif ($when === 'after') {
                $disable_time->add($interval);
                $result = $now >= $disable_time;
            } else {
                throw new Exception("Invalid 'when' value: " . $when);
            }

            error_log("Event time: " . $event_date->format('Y-m-d H:i:s T'));
            error_log("Button should be disabled at " . $disable_time->format('Y-m-d H:i:s T'));

            return $result;
        } else {
            error_log("Button is never disabled for this product");
            return false;
        }
    } catch (Exception $e) {
        error_log("Error in viristar_should_disable_add_to_cart for product ID " . $product_id . ": " . $e->getMessage());
        return false;
    }
}

function update_product_stock_status($product_id) {
    $product = wc_get_product($product_id);

    if ($product && viristar_should_disable_add_to_cart($product_id)) {
        $product->set_stock_status('outofstock');
        $product->save();
    }
}

function disable_product_on_view() {
    if (is_product()) {
        global $post;
        $product_id = $post->ID;
        update_product_stock_status($product_id);
    }
}
add_action('wp', 'disable_product_on_view');

function disable_products_in_custom_loop($product_ids) {
    if (!is_array($product_ids)) {
        $product_ids = array($product_ids);
    }

    foreach ($product_ids as $product_id) {
        update_product_stock_status($product_id);
    }
}