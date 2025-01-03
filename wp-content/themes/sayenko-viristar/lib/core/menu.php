<?php

/**
 * Retrieves the menu slug from a menu object or ID.
 *
 * @param mixed $menu The menu object or ID.
 * @return string|false The menu slug if found, otherwise false.
 */
function get_menu_slug_from_id($menu)
{
    // Check if the input is an object
    if (is_object($menu)) {
        // Check if the object has a slug property
        if (isset($menu->slug)) {
            return $menu->slug;
        }
        return false;
    }

    // If the input is not an object, assume it's an ID and get the menu object by ID
    if (is_numeric($menu)) {
        $menu_obj = wp_get_nav_menu_object($menu);

        // Check if the menu object exists and return the slug, otherwise return false
        if ($menu_obj && !is_wp_error($menu_obj)) {
            return $menu_obj->slug;
        }
    }

    return false;
}
