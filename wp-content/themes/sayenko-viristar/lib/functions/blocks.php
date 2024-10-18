<?php

// Blocks

add_filter( 'should_load_separate_core_block_assets', '__return_true' );


// ACF Blocks: Alter message for all ACF blocks that contain no ACF fields

function no_fields_message($message) {

    $message = __('No fields for this block', 'projectname');
  
    return $message;
  
  }
  
  add_filter ('acf/blocks/no_fields_assigned_message', 'no_fields_message', 10, 2);


add_action( 'wp_enqueue_scripts', function() {
	
	wp_register_script( 'splide', get_theme_file_uri( 'dist/scripts/splide.js' ), ['jquery'], '', ['in_footer' => true, 'strategy' => 'defer'] );
	
	wp_register_style( 'splide', get_theme_file_uri( 'dist/styles/splide.css' ) );

    wp_register_style( 'splide-theme', get_theme_file_uri( 'dist/styles/splide.css' ) );


}, 10 );


add_filter( 'acf/blocks/wrap_frontend_innerblocks', 'acf_should_wrap_innerblocks', 10, 2 );
function acf_should_wrap_innerblocks( $wrap, $name ) {
    if ( in_array( $name,  ['acf/hero'] ) ) {
        return false;
    }
    return true;
}

/**
 * Register our custom block style
 *
 * @return void
 */
function wp_dev_register_block_style() {

	register_block_style(
		'core/list',
		array(
			'name'  => 'columns-2', // .is-style-check
			'label' => 'Two Columns',
		)
	);

	register_block_style(
		'core/list',
		array(
			'name'  => 'columns-3', // .is-style-check
			'label' => 'Three Columns',
		)
	);
}
add_action( 'init', 'wp_dev_register_block_style' );


function parse_acf_block_content($content) {
    // Ensure the WordPress function is available
    if (!function_exists('parse_blocks')) {
        return []; // Return empty array if function doesn't exist
    }

    // Parse the blocks
    $blocks = parse_blocks($content);

    // Process the parsed blocks
    $results = [];
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'acf/content-tab') {
            $attributes = $block['attrs'];

            // Extract the inner content by removing the comments
            $inner_content = preg_replace('/<!--.*?-->/s', '', $block['innerHTML']);
            $inner_content = trim($inner_content);

            $results[] = [
                'block_name' => 'content-tab',
                'attributes' => $attributes,
                'content' => $inner_content
            ];
        }
    }

    return $results;
}
/* function sd_parse_block_content($content, $class = 'tab-content') {
     // Create a new DOMDocument
	 $dom = new DOMDocument();

	 // Preserve white space
	 $dom->preserveWhiteSpace = true;
	 $dom->formatOutput = false;
 
	 // Load the HTML string, using the @ to suppress warnings for malformed HTML
	 @$dom->loadHTML('<div>' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
 
	 // Create a new DOMXPath object
	 $xpath = new DOMXPath($dom);
 
	 // Query for all div elements with the specified class
	 $divs = $xpath->query("//div[contains(@class, '$class')]");
 
	 $results = [];
 
	 // Iterate through the matching divs
	 foreach ($divs as $div) {
		 $attributes_json = $div->getAttribute('data-attributes');
		 $attributes = json_decode($attributes_json, true) ?: [];
 
		 // Get inner HTML
		 $innerHtml = '';
		 foreach ($div->childNodes as $child) {
			 $innerHtml .= $dom->saveHTML($child);
		 }
 
		 $results[] = array_merge($attributes, [
			 'content' => $innerHtml
		 ]);
	 }
 
	 return $results;
} */

function sd_parse_block_content($content) {
    $pattern = '/<!-- acf-block-data (.*?) -->\s*([\s\S]*?)\s*<!-- \/acf-block-data -->/';
    preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

    $result = [];

    foreach ($matches as $match) {
        $json_data = html_entity_decode($match[1]);
        $data = json_decode($json_data, true);

        if ($data !== null) {
            $block_content = trim($match[2]);
            // Remove leading and trailing newlines and extra spaces
            $block_content = preg_replace('/^\s+|\s+$/m', '', $block_content);

            $result[] = [
                'title' => $data['title'] ?? '',  // Use null coalescing operator to handle cases where title might not be set
                'content' => $block_content
            ];
        }
    }

    return $result;
}