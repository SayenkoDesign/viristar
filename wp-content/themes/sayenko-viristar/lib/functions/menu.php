<?php
// Menu



/**
 *
 * Adds a filter to wrap the menu item text in a span element.
 *
 * @param string $item_output The HTML output of the menu item.
 * @param object $item The current menu item object.
 * @param int $depth The depth of the current menu item.
 * @param object $args An object containing the arguments used to generate the menu.
 * @return string The modified HTML output of the menu item.
 */
add_filter('walker_nav_menu_start_el', 'wrap_menu_item_text_in_span', 10, 4);

function wrap_menu_item_text_in_span($item_output, $item, $depth, $args) {
    // Check if this is the primary menu. Adjust 'primary' to your specific theme location
    if ($args->theme_location == 'primary' || $args->theme_location == 'secondary') {
        // Use regex to find the menu link text and wrap it in a span
        $item_output = preg_replace('/(<a.*?>)(.*?)(<\/a>)/i', '$1<span>$2</span>$3', $item_output);
    }

    return $item_output;
}


/**
 * Adds an SVG icon to a specific menu item.
 *
 * This function is used as a callback for the 'walker_nav_menu_start_el' filter.
 * It checks if the current menu item has the class 'special' and appends an SVG icon to it.
 *
 * @param string $item_output The HTML output of the menu item.
 * @param object $item        The current menu item object.
 * @param int    $depth       The depth of the current menu item.
 * @param array  $args        An array of arguments passed to the walker.
 * @return string The modified HTML output of the menu item.
 */
add_filter('walker_nav_menu_start_el', 'add_svg_to_specific_menu_item', 10, 4);

function add_svg_to_specific_menu_item($item_output, $item, $depth, $args) {
    // Define your SVG icon
    $svg_icon = '<svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
	<g id="Group">
	<path id="Vector" d="M6.50002 7.4361C8.41922 7.4361 9.98003 5.39936 9.98003 3.48048C9.98003 1.56145 8.41922 0 6.50002 0C4.58115 0 3.02002 1.56129 3.02002 3.48032C3.02018 5.39936 4.58131 7.4361 6.50002 7.4361Z" fill="currentColor"/>
	<path id="Vector_2" d="M9.11271 7.47162C8.43751 7.98594 7.59539 8.29208 6.68318 8.29208H6.31682C5.40461 8.29208 4.56233 7.98594 3.88729 7.47162C1.68314 7.82793 0 9.73853 0 12.043C0 13.309 2.91009 14.3354 6.5 14.3354C10.0899 14.3354 13 13.309 13 12.043C13 9.73853 11.3167 7.82793 9.11271 7.47162Z" fill="currentColor"/>
	</g>
	</svg>
	';

    // Check if the current item has the class 'special'
    if (in_array('menu-item-user', $item->classes)) {
        // Append the SVG icon to the item
        $item_output = str_replace( '</a>', $svg_icon . '</a>', $item_output);
    }

    return $item_output;
}