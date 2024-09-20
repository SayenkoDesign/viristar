<?php

/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

function sd_child_theme_setup()
{

	//* Child theme (do not remove)
	define('CHILD_THEME_VERSION', filemtime(get_stylesheet_directory() . '/style.css'));

	define('THEME_DIR', get_stylesheet_directory());
	define('THEME_URL', get_stylesheet_directory_uri());
	define('THEME_DIST', THEME_URL . '/dist');
	
	define('THEME_LANG', THEME_URL . '/languages');
	define('THEME_LIB', THEME_URL . '/lib');
	define('THEME_STYLES', THEME_DIST . '/styles');
	define('THEME_IMG', THEME_DIST . '/images');
	define('THEME_SCRIPTS', THEME_DIST . '/scripts');
	define('THEME_ICONS', THEME_URL . '/assets/icons');

	// -- Responsive embeds
	add_theme_support('responsive-embeds');

	add_theme_support( 'custom-spacing' );

	add_theme_support( 'editor-styles' );
    add_editor_style( 'dist/styles/editor-style.css' );

	remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'sd_child_theme_setup', 15);
	

/**
 * Explicitly load theme files
 */
array_map(function ($file) {
	if (!$filepath = locate_template($file)) {
		trigger_error(sprintf(__('Error locating %s for inclusion', ''), $file), E_USER_ERROR);
	}

	require_once $filepath;
}, [
	// Classes
	'lib/classes/class-controls-stack.php',
	'lib/classes/class-element-base.php',
	'lib/classes/class-tabs.php',
	'lib/classes/class-accordion.php',
	'lib/classes/class-acf-blocks.php',
	'lib/classes/class-acf-block.php',

	// Core
	'lib/core/admin.php',
	'lib/core/attributes.php',
	'lib/core/markup.php',
	'lib/core/format.php',
	'lib/core/media.php',
	'lib/core/scripts.php',
	'lib/core/assets.php',
	'lib/core/icon.php',
	'lib/core/theme.php',
	'lib/core/menu.php',
	'lib/core/template-tags.php',

	// GeneratePress
	'lib/generatepress/defaults.php',
	'lib/generatepress/colors.php',
	'lib/generatepress/icons.php',
	'lib/generatepress/hooks.php',

	// Woocommerce
	'lib/woocommerce/hooks.php',
	'lib/woocommerce/disable-add-to-cart.php',

	// Post Types
	'lib/post-types/init.php',

	// ACF + ACF Blocks
	'lib/functions/blog.php',
	'lib/functions/post.php',

	// Theme
	'lib/functions/string.php',
	'lib/functions/html.php',
	'lib/functions/acf.php',
	'lib/functions/gravity-forms.php',
	'lib/functions/redirects.php',
	'lib/functions/blocks.php',
	'lib/functions/menu.php',
	'lib/functions/theme.php',
	'lib/functions/facetwp.php',
	'lib/functions/clients.php',
	'lib/functions/course.php',
	'lib/functions/mega-menu.php',
	'lib/functions/testimonial.php',
	'lib/functions/modal.php',
	'lib/functions/woocommerce.php',

	'lib/classes/class-course.php',
	'lib/classes/class-course-grid.php',
	'lib/classes/class-course-finder.php',
	
	
]);


/* add_action('generate_after_footer', function() {
	if (is_page()) {
		global $post;
		echo get_related_pages(get_the_ID());
	}
	
}, 0); */

function get_related_pages($post_id) {
    // Check if user is logged in and has the correct email domain
    if (!is_user_logged_in()) {
        return ''; // Return empty if user is not logged in
    }

    $current_user = wp_get_current_user();
    if (!preg_match('/@sayenkodesign\.com$/i', $current_user->user_email)) {
        return ''; // Return empty if user's email doesn't end with @sayenkodesign.com
    }

    // Get all ancestors of the current page
    $ancestors = get_post_ancestors($post_id);
    $root_page_id = empty($ancestors) ? $post_id : end($ancestors);

    // Get all pages
    $pages = get_pages(array(
        'sort_column' => 'menu_order',
        'post_type' => 'page',
    ));

    // Get the title of the top-level page
    $top_level_title = get_the_title($root_page_id);

    // Build the hierarchical list
    $list_content = build_hierarchical_list($pages, $root_page_id);

    // Wrap the content in a div with the top-level page as a heading
    return '
    <style>
    .related-pages-hierarchy {
        background-color: #fefefe;
        padding: 30px;
        margin-top: 30px;
    }

	.related-pages-hierarchy > ul > li {
        margin-bottom: 24px;
    }  

	.related-pages-hierarchy ul li:first-child {
        margin-top: 12px;
    }  

    .related-pages-hierarchy ul ul {
        margin-bottom: 24px;
    }   
	
	.related-pages-hierarchy ul ul li {
        margin-bottom: 12px;
    }  

	.related-pages-hierarchy-anchor {
		position: fixed;
		top: 25%;
		right: 15px;
		background: black;
		color: white;
		text-decoration: none;	
		padding: 10px;
		z-index: 999;
		font-size: 2rem;
		text-align: center;
	}

	.related-pages-hierarchy-anchor:hover {
		color: white;

	}

    </style>
	<a href="#related-pages-hierarchy" class="related-pages-hierarchy-anchor">&darr;</a>
    <div id="related-pages-hierarchy" class="related-pages-hierarchy">
        <h3>' . esc_html($top_level_title) . '</h3>
        ' . $list_content . '
    </div>';
}

function build_hierarchical_list($pages, $parent_id) {
    $children = array_filter($pages, function($page) use ($parent_id) {
        return $page->post_parent == $parent_id;
    });

    if (empty($children)) {
        return '';
    }

    $output = '<ul>';
    foreach ($children as $child) {
        $output .= '<li>';
        $output .= '<a href="' . get_permalink($child->ID) . '">' . get_the_title($child->ID) . '</a>';
        $output .= build_hierarchical_list($pages, $child->ID);
        $output .= '</li>';
    }
    $output .= '</ul>';

    return $output;
}



/* add_shortcode ('get_user_certificate_moodle', 'get_user_certificate_moodle');
function get_user_certificate_moodle() {
	global $current_user;
	$ch = curl_init();
    $headers = array(
		'Accept: application/json',
		'Content-Type: application/json',
    );
	$email = 'stephanie@viristar.com';
	$email = urlencode($email);
	$moodleURL = get_field('moodle_url', 'option');
	$token = get_field('access_token', 'option');
	$url =$moodleURL."/webservice/rest/server.php?wstoken=".$token."&wsfunction=mod_customcert2_get_user_course_certificates&email=".$email."&moodlewsrestformat=json";
    
	var_dump($url);
	
	curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
	$data = json_decode($response);

	curl_close($ch);

	var_dump($data);
	
	

} */
