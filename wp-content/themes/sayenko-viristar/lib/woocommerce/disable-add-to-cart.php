<?php

if (!class_exists('WooCommerce')) {
    return;
}

add_filter('woocommerce_product_is_in_stock', 'viristar_check_product_stock_by_id', 10, 2);
/**
 * Check if a product is in stock by its ID.
 *
 * This function is a custom WooCommerce filter that checks the stock status of a product.
 *
 * @param bool $is_in_stock Whether the product is in stock.
 * @param WC_Product $product The WooCommerce product object.
 * @return bool Modified stock status.
 */
function viristar_check_product_stock_by_id($is_in_stock, $product)
{
    // Get the product ID
    $product_id = $product->get_id();

    $course_type = get_field('course_type', $product_id);

    if (empty($course_type)) {
        return $is_in_stock;
    }

    // Has the date passed
    $start_date_unix_timestamp = get_field('start_date_unix_timestamp', $product_id);
    $current_time = time();

    if ($current_time >= $start_date_unix_timestamp) {
        $is_in_stock = false;
    }

    return $is_in_stock;
}

add_action('acf/save_post', 'update_start_date_unix_timestamp_on_save', 20);
function update_start_date_unix_timestamp_on_save($post_id)
{
    // Skip autosave and revisions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    // Check if the post type is 'product'
    $post_type = get_post_type($post_id);

    if ($post_type !== 'product') {
        return;
    }

    // Check for Course Type
    $course_type = get_field('course_type', $post_id);

    if (empty($course_type)) {
        return;
    }

    // Calculate start_date_unix_timestamp
    $start_timestamp = viristar_calculate_disable_time($post_id);

    // If the calculated timestamp is valid, update the start_date_unix_timestamp field
    if ($start_timestamp) {
        update_field('start_date_unix_timestamp', $start_timestamp, $post_id);
    } else {
        error_log("Post ID $post_id - start_timestamp calculation returned false or invalid value.");
    }
}

// Existing function to calculate start_date_unix_timestamp
function viristar_calculate_disable_time($product_id)
{
    $start_date = get_field('start_date', $product_id);
    $timezone = get_field('timezone', $product_id);
    $disable_buy_now = get_field('disable_buy_now', $product_id);

    if (empty($start_date) || empty($timezone)) {
        return false;
    }

    try {
        $timezone_object = new DateTimeZone($timezone);
        $event_date = DateTime::createFromFormat('Y-m-d H:i:s', $start_date, $timezone_object);

        if (!$event_date) {
            throw new Exception("Invalid date format for start_date: " . $start_date);
        }

        // If disable_buy_now is not configured, return the start date's timestamp
        if (empty($disable_buy_now) || strtolower($disable_buy_now['when']) === 'never') {
            $event_date->setTimezone(new DateTimeZone('UTC'));
            $timestamp = $event_date->getTimestamp();
            return $timestamp;
        }

        $amount = intval($disable_buy_now['time']);
        $unit = strtolower($disable_buy_now['units']);
        $when = strtolower($disable_buy_now['when']);
        $interval_string = $unit === 'days' ? "P{$amount}D" : "PT{$amount}H";
        $interval = new DateInterval($interval_string);

        // Adjust event date based on 'when' value
        if ($when === 'before') {
            $event_date->sub($interval);
        } elseif ($when === 'after') {
            $event_date->add($interval);
        } else {
            // throw new Exception("Invalid 'when' value: " . $when);
        }

        // Set to UTC for consistent timestamp generation
        $event_date->setTimezone(new DateTimeZone('UTC'));
        $final_timestamp = $event_date->getTimestamp();

        return $final_timestamp;
    } catch (Exception $e) {
        error_log("Error in viristar_calculate_disable_time for product ID " . $product_id . ": " . $e->getMessage());
        return false;
    }
}
