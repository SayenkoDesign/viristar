<?php

function nl_2_li($text) {

	if( empty( $text ) ) {
		return '';
	}

    // Split the text into an array by newlines
    $lines = explode("\n", trim($text));
    
    // Filter out empty lines and wrap each non-empty line in <li> tags
    $list_items = array_map(function($line) {
        $trimmed = trim($line);
        return $trimmed ? "<li>" . esc_html($trimmed) . "</li>" : '';
    }, $lines);
    
    // Filter out any empty strings resulting from blank lines
    $list_items = array_filter($list_items);
    
    // Join the list items into a single string
    return implode("\n", $list_items);
}