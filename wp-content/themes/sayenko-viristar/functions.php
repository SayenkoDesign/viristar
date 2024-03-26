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
	'lib/core/template-tags.php',

	// GeneratePress
	'lib/generatepress/defaults.php',
	'lib/generatepress/colors.php',
	'lib/generatepress/icons.php',

	// Post Types
	'lib/post-types/init.php',

	// ACF + ACF Blocks
	'lib/functions/blog.php',
	'lib/functions/post.php',

	// Theme
	'lib/functions/gravity-forms.php',
	'lib/functions/redirects.php',
	'lib/functions/blocks.php',
	'lib/functions/menu.php',
	'lib/functions/theme.php',
	/* 'lib/functions/case-studies.php',
	'lib/functions/case-study.php',
	'lib/functions/white-papers.php',
	'lib/functions/white-paper.php', */
	
	
]);